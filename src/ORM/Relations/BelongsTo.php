<?php
/*
 * @Author: 7123
 * @Date: 2025-11-15 19:13:51
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:17:58
 */

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
     * 创建一个新的属于关系实例
     *
     * @param QueryBuilder $query 查询构建器实例
     * @param BaseModel $child 子模型实例
     * @param string $foreignKey 外键名
     * @param string $ownerKey 所有者键名
     */
    public function __construct(QueryBuilder $query, BaseModel $child, string $foreignKey, string $ownerKey)
    {
        $this->query = $query;
        $this->child = $child;
        $this->foreignKey = $foreignKey;
        $this->ownerKey = $ownerKey;
    }

    /**
     * 获取关系的结果
     *
     * @return BaseModel|null 关联的模型实例，不存在则返回null
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
