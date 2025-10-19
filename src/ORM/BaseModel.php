<?php

namespace Luminode\Core\ORM;

use DI\Container;
use Luminode\Core\Database;
use PDO;

abstract class BaseModel
{
    protected static ?Container $container = null;

    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $attributes = [];
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
            $this->setAttribute($key, $value);
        }
        return $this;
    }

    protected function getTable(): string
    {
        if (isset($this->table)) {
            return $this->table;
        }
        $className = substr(strrchr(get_class($this), "\\"), 1);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . 's';
    }

    public static function find($id)
    {
        $model = new static();
        $table = $model->getTable();
        $primaryKey = $model->primaryKey;

        $stmt = $model->db->query("SELECT * FROM {$table} WHERE {$primaryKey} = ?", [$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $model->fill($data);
            $model->exists = true;
            return $model;
        }

        return null;
    }

    public static function all(): array
    {
        $model = new static();
        $table = $model->getTable();

        $stmt = $model->db->query("SELECT * FROM {$table}");
        $results = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $data) {
            $instance = new static($data);
            $instance->exists = true;
            $results[] = $instance;
        }
        return $results;
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

    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }
}
