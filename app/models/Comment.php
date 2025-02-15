<?php

// 定义一个名为 Comment 的类，该类继承自 BaseModel
class Comment extends BaseModel {
    // 定义一个静态属性 $table，用于指定该模型对应的数据库表名
    protected static $table = 'comments';

    // 定义一个名为 replies 的方法，用于获取当前评论的所有回复
    public function replies() {
        // 使用 hasMany 方法建立一对多关系，参数1为关联的模型类名，参数2为外键字段名
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // 定义一个静态方法 withReplies，用于获取所有顶级评论（即没有父评论的评论）
    public static function withReplies() {
        // 使用 self::all() 方法获取所有评论
        $comments = self::all();
        // 使用 array_filter 方法过滤出所有 parent_id 为 null 的评论，即顶级评论
        return array_filter($comments, function($comment) {
            return $comment->parent_id === null;
        });
    }
}