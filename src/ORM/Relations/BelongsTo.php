<?php

namespace Luminode\Core\ORM\Relations;

use Luminode\Core\ORM\BaseModel;
use Luminode\Core\ORM\QueryBuilder;

class BelongsTo
{
    protected QueryBuilder $query;
    protected BaseModel $child;
    protected string $foreignKey;
    protected string $ownerKey;

    /**
     * Create a new belongs-to relationship instance.
     *
     * @param QueryBuilder $query
     * @param BaseModel $child
     * @param string $foreignKey
     * @param string $ownerKey
     */
    public function __construct(QueryBuilder $query, BaseModel $child, string $foreignKey, string $ownerKey)
    {
        $this->query = $query;
        $this->child = $child;
        $this->foreignKey = $foreignKey;
        $this->ownerKey = $ownerKey;
    }

    /**
     * Get the results of the relationship.
     *
     * @return BaseModel|null
     */
    public function getResults(): ?BaseModel
    {
        $foreignKeyValue = $this->child->getAttribute($this->foreignKey);
        return $this->query->where($this->ownerKey, '=', $foreignKeyValue)->firstModel();
    }

    public function getQuery(): QueryBuilder
    {
        return $this->query;
    }

    public function getForeignKey(): string
    {
        return $this->foreignKey;
    }

    public function getOwnerKey(): string
    {
        return $this->ownerKey;
    }
}
