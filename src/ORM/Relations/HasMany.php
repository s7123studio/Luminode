<?php

namespace Luminode\Core\ORM\Relations;

use Luminode\Core\ORM\BaseModel;
use Luminode\Core\ORM\QueryBuilder;

class HasMany
{
    protected QueryBuilder $query;
    protected BaseModel $parent;
    protected string $foreignKey;
    protected string $localKey;

    /**
     * Create a new has-many relationship instance.
     *
     * @param QueryBuilder $query
     * @param BaseModel $parent
     * @param string $foreignKey
     * @param string $localKey
     */
    public function __construct(QueryBuilder $query, BaseModel $parent, string $foreignKey, string $localKey)
    {
        $this->query = $query;
        $this->parent = $parent;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    /**
     * Get the results of the relationship.
     *
     * @return array
     */
    public function getResults(): array
    {
        $localKeyValue = $this->parent->getAttribute($this->localKey);
        return $this->query->where($this->foreignKey, '=', $localKeyValue)->getModels();
    }

    public function getQuery(): QueryBuilder
    {
        return $this->query;
    }

    public function getForeignKey(): string
    {
        return $this->foreignKey;
    }

    public function getLocalKey(): string
    {
        return $this->localKey;
    }
}
