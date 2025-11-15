<?php

namespace Luminode\Core\ORM;

use DI\Container;
use Luminode\Core\Database;
use Luminode\Core\ORM\Relations\BelongsTo;
use Luminode\Core\ORM\Relations\HasMany;
use PDO;
use Luminode\Core\ORM\QueryBuilder;

abstract class BaseModel
{
    protected static ?Container $container = null;

    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $attributes = [];
    
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected array $fillable = [];

    /**
     * The loaded relationships for the model.
     * @var array
     */
    protected array $relations = [];

    protected bool $exists = false;

    public function __construct(array $attributes = [])
    {
        if (self::$container === null) {
            throw new \Exception('BaseModel not initialized. Please call setContainer() in your bootstrap file.');
        }
        $this->db = self::$container->get(Database::class);

        $this->fill($attributes);
    }

    public static function setContainer(Container $container): void
    {
        self::$container = $container;
    }

    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
        return $this;
    }

    protected function isFillable(string $key): bool
    {
        return in_array($key, $this->fillable);
    }

    protected function getTable(): string
    {
        if (isset($this->table)) {
            return $this->table;
        }
        $className = substr(strrchr(get_class($this), "\\"), 1);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . 's';
    }

    public static function query(): QueryBuilder
    {
        if (self::$container === null) {
            throw new \Exception('BaseModel not initialized. Please call setContainer() in your bootstrap file.');
        }
        $model = new static(); // Instantiate to get table name and db instance
        return new QueryBuilder(
            self::$container->get(Database::class),
            $model->getTable(),
            static::class // Pass the current model class name
        );
    }

    public static function where(string $column, string $operator, $value): QueryBuilder
    {
        return static::query()->where($column, $operator, $value);
    }

    public static function orderBy(string $column, string $direction = 'ASC'): QueryBuilder
    {
        return static::query()->orderBy($column, $direction);
    }

    public static function limit(int $limit): QueryBuilder
    {
        return static::query()->limit($limit);
    }

    public static function offset(int $offset): QueryBuilder
    {
        return static::query()->offset($offset);
    }

    public static function find($id)
    {
        $model = new static();
        $table = $model->getTable();
        $primaryKey = $model->primaryKey;

        $stmt = $model->db->query("SELECT * FROM {$table} WHERE {$primaryKey} = ?", [$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $model->forceFill($data);
            $model->exists = true;
            return $model;
        }

        return null;
    }

    public static function all(): array
    {
        return static::query()->getModels();
    }

    public static function get(): array
    {
        return static::query()->getModels();
    }

    public static function first()
    {
        return static::query()->firstModel();
    }

    /**
     * Create a collection of models from a plain array.
     *
     * @param array $items
     * @return array
     */
    public static function hydrate(array $items): array
    {
        $models = [];
        foreach ($items as $item) {
            $model = new static();
            $model->forceFill($item);
            $model->exists = true;
            $models[] = $model;
        }
        return $models;
    }

    protected function hasMany(string $related, string $foreignKey = null, string $localKey = null): HasMany
    {
        $relatedInstance = new $related();
        $foreignKey = $foreignKey ?: strtolower(substr(strrchr(get_class($this), "\\"), 1)) . '_' . $this->primaryKey;
        $localKey = $localKey ?: $this->primaryKey;

        return new HasMany($relatedInstance::query(), $this, $foreignKey, $localKey);
    }

    protected function belongsTo(string $related, string $foreignKey = null, string $ownerKey = null): BelongsTo
    {
        $relatedInstance = new $related();
        $foreignKey = $foreignKey ?: strtolower(substr(strrchr($related, "\\"), 1)) . '_' . $relatedInstance->primaryKey;
        $ownerKey = $ownerKey ?: $relatedInstance->primaryKey;

        return new BelongsTo($relatedInstance::query(), $this, $foreignKey, $ownerKey);
    }

    protected function forceFill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }

    public function save(): bool
    {
        $table = $this->getTable();
        if ($this->exists) {
            // UPDATE
            $updates = [];
            $params = [];
            foreach ($this->attributes as $key => $value) {
                if ($key !== $this->primaryKey) {
                    $updates[] = "`{$key}` = ?";
                    $params[] = $value;
                }
            }
            $params[] = $this->attributes[$this->primaryKey];
            $sql = "UPDATE {$table} SET " . implode(', ', $updates) . " WHERE `{$this->primaryKey}` = ?";
            $this->db->query($sql, $params);
        } else {
            // INSERT
            $columns = array_keys($this->attributes);
            $placeholders = array_fill(0, count($columns), '?');
            $sql = "INSERT INTO {$table} (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")";
            $this->db->query($sql, array_values($this->attributes));
            $this->setAttribute($this->primaryKey, $this->db->lastInsertId());
            $this->exists = true;
        }
        return true;
    }
    
    public function delete(): bool
    {
        if (!$this->exists) {
            return false;
        }
        $table = $this->getTable();
        $sql = "DELETE FROM {$table} WHERE `{$this->primaryKey}` = ?";
        $this->db->query($sql, [$this->getAttribute($this->primaryKey)]);
        $this->exists = false;
        return true;
    }

    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function setRelation(string $key, $value): void
    {
        $this->relations[$key] = $value;
    }

    public function __get(string $key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->getAttribute($key);
        }

        if (array_key_exists($key, $this->relations)) {
            return $this->relations[$key];
        }

        if (method_exists($this, $key)) {
            $relation = $this->$key();

            if ($relation instanceof HasMany || $relation instanceof BelongsTo) {
                $results = $relation->getResults();
                $this->relations[$key] = $results;
                return $results;
            }
        }

        return null;
    }

    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }
}
