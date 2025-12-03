<?php
/*
 * @Author: 7123
 * @Date: 2025-11-15 19:10:57
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:18:18
 */

namespace Luminode\Core\ORM;

use Luminode\Core\Database;
use Luminode\Core\ORM\Relations\BelongsTo;
use Luminode\Core\ORM\Relations\HasMany;
use PDO;

class QueryBuilder
{
    protected Database $db;
    protected string $table;
    protected string $modelClass;

    protected array $wheres = [];
    protected array $bindings = [];
    protected array $orders = [];
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $eagerLoad = [];

    public function __construct(Database $db, string $table, string $modelClass)
    {
        $this->db = $db;
        $this->table = $table;
        $this->modelClass = $modelClass;
    }

    public function with(string|array $relations): self
    {
        $this->eagerLoad = is_array($relations) ? $relations : [$relations];
        return $this;
    }

    public function where(string $column, string $operator, $value): self
    {
        $this->wheres[] = "`{$column}` {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function whereIn(string $column, array $values): self
    {
        if (empty($values)) {
            $this->wheres[] = '0=1'; // Always false if IN array is empty
            return $this;
        }
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $this->wheres[] = "`{$column}` IN ({$placeholders})";
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orders[] = "`{$column}` {$direction}";
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function getModels(): array
    {
        $rawResults = $this->get();
        if (empty($rawResults)) {
            return [];
        }

        $models = call_user_func([$this->modelClass, 'hydrate'], $rawResults);

        if (!empty($this->eagerLoad)) {
            $this->loadRelations($models);
        }

        return $models;
    }

    public function firstModel(): ?BaseModel
    {
        $rawResult = $this->first();
        if ($rawResult === null) {
            return null;
        }

        $model = call_user_func([$this->modelClass, 'hydrate'], [$rawResult])[0];

        if (!empty($this->eagerLoad)) {
            $this->loadRelations([$model]);
        }

        return $model;
    }

    protected function get(): array
    {
        $sql = $this->toSql();
        $stmt = $this->db->query($sql, $this->bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function first(): ?array
    {
        $this->limit(1);
        $results = $this->get();
        return empty($results) ? null : $results[0];
    }

    protected function toSql(): string
    {
        $sql = "SELECT * FROM `{$this->table}`";

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        if (!empty($this->orders)) {
            $sql .= " ORDER BY " . implode(', ', $this->orders);
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }

    protected function loadRelations(array $models): void
    {
        foreach ($this->eagerLoad as $relationName) {
            $relation = (new $this->modelClass)->$relationName();

            if ($relation instanceof HasMany) {
                $this->eagerLoadHasMany($models, $relationName, $relation);
            } elseif ($relation instanceof BelongsTo) {
                $this->eagerLoadBelongsTo($models, $relationName, $relation);
            }
        }
    }

    protected function eagerLoadHasMany(array $models, string $relationName, HasMany $relation): void
    {
        $localKeys = array_unique(array_map(fn($model) => $model->getAttribute($relation->getLocalKey()), $models));
        
        $relatedModels = $relation->getQuery()->whereIn($relation->getForeignKey(), $localKeys)->getModels();

        $groupedByForeignKey = [];
        foreach ($relatedModels as $relatedModel) {
            $groupedByForeignKey[$relatedModel->getAttribute($relation->getForeignKey())][] = $relatedModel;
        }

        foreach ($models as $model) {
            $localKeyValue = $model->getAttribute($relation->getLocalKey());
            $matchedModels = $groupedByForeignKey[$localKeyValue] ?? [];
            $model->setRelation($relationName, $matchedModels);
        }
    }

    protected function eagerLoadBelongsTo(array $models, string $relationName, BelongsTo $relation): void
    {
        $foreignKeys = array_unique(array_filter(array_map(fn($model) => $model->getAttribute($relation->getForeignKey()), $models)));

        if (empty($foreignKeys)) {
            return;
        }

        $relatedModels = $relation->getQuery()->whereIn($relation->getOwnerKey(), $foreignKeys)->getModels();

        $keyedByOwnerKey = [];
        foreach ($relatedModels as $relatedModel) {
            $keyedByOwnerKey[$relatedModel->getAttribute($relation->getOwnerKey())] = $relatedModel;
        }

        foreach ($models as $model) {
            $foreignKeyValue = $model->getAttribute($relation->getForeignKey());
            $matchedModel = $keyedByOwnerKey[$foreignKeyValue] ?? null;
            $model->setRelation($relationName, $matchedModel);
        }
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }
}
