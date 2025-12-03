<?php
/*
 * @Author: 7123
 * @Date: 2025-11-15 19:13:38
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:18:04
 */

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
     * 创建一个新的一对多关系实例
     *
     * @param QueryBuilder $query 查询构建器实例
     * @param BaseModel $parent 父模型实例
     * @param string $foreignKey 外键名
     * @param string $localKey 本地键名
     */
    public function __construct(QueryBuilder $query, BaseModel $parent, string $foreignKey, string $localKey)
    {
        $this->query = $query;
        $this->parent = $parent;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    /**
     * 获取关系的结果
     *
     * @return array 关联模型数组
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
