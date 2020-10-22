<?php

namespace floor12\comments\models;

use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[Comment]].
 *
 * @see Comment
 */
class CommentQuery extends ActiveQuery
{
    /** Published comments
     * @return CommentQuery
     */
    public function active()
    {
        return $this->andWhere(['status' => CommentStatus::PUBLISHED]);
    }

    /** Ordered by abswer
     * @param $classname
     * @param $object_id
     * @return CommentQuery
     */
    public function byObject($classname, $object_id)
    {
        return $this->andWhere([
            'class' => $classname,
            'object_id' => $object_id,
        ]);
    }

    /**
     * @param $sort Result order: "+" or "-"
     * @return CommentQuery
     */
    public function tree($sort)
    {
        $expression = new Expression("CASE WHEN parent_id = 0 THEN {$sort}id ELSE {$sort}parent_id END AS sort");
        return $this->addSelect(["*", $expression])->orderBy("sort, created");
    }

    /**
     * {@inheritdoc}
     * @return Comment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Comment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
