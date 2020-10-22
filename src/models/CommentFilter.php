<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 04.08.2018
 * Time: 14:24
 */

namespace floor12\comments\models;


use Throwable;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CommentFilter extends Model
{
    public $status;
    public $filter;
    public $class;
    public $order;
    public $object_id;

    private $_query;

    public function rules()
    {
        return [
            [['status', 'object_id'], 'integer'],
            [['filter', 'class'], 'string']
        ];
    }

    public function dataProvider()
    {
        $this->_query = Comment::find()
            ->tree($this->order)
            ->andFilterWhere(['=', 'status', $this->status])
            ->andFilterWhere(['=', 'object_id', $this->object_id])
            ->andFilterWhere(['LIKE', 'content', $this->filter])
            ->andFilterWhere(['=', 'class', $this->class]);

        return new ActiveDataProvider([
            'query' => $this->_query
        ]);
    }

    public function getCommentObjectClasses(): array
    {
        $classNames = Comment::find()
            ->distinct()
            ->select('class')
            ->orderBy('class')
            ->indexBy('class')
            ->column();

        if (empty($classNames)) {
            return [];
        }

        foreach ($classNames as $className) {
            try {
                $readbleName = $className::getHumanReadbleModelName();
                $classNames[$className] = $readbleName;
            } catch (Throwable $exception) {
                Yii::error($exception->getMessage());
            }
        }
        return $classNames;
    }
}
