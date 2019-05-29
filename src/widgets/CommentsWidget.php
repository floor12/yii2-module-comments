<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29.07.2018
 * Time: 20:43
 */

namespace floor12\comments\widgets;

use floor12\comments\assets\CommentsAsset;
use floor12\comments\Module;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

class CommentsWidget extends Widget
{
    public $formPosition;
    public $object_id;
    public $classname;
    public $showForm = false;


    private $_html;
    private $_block_id;
    private $_commentFormBlock_id;
    private $_commentFormBlock;
    private $_commentListBlock_id;
    private $_commentListBlock;
    private $_commentBlock_id;
    private $_f12CommentFormUrl;
    private $_f12CommentIndexUrl;
    private $_f12CommentDeleteUrl;
    private $_f12CommentUpdateUrl;


    public function init()
    {
        CommentsAsset::register($this->view);
        $this->_f12CommentIndexUrl = Url::toRoute(['/comments/frontend/index']);
        $this->_f12CommentFormUrl = Url::toRoute(['/comments/frontend/form']);
        $this->_f12CommentDeleteUrl = Url::toRoute(['/comments/frontend/delete']);
        $this->_f12CommentUpdateUrl = Url::toRoute(['/comments/frontend/edit']);

        if (!$this->formPosition)
            $this->formPosition = Yii::$app->getModule('comments')->formPosition;

        $this->_block_id = substr(md5(rand(9999, 99999)), 0, 5);
        $this->_commentFormBlock_id = "commentForm{$this->_block_id}";
        $this->_commentListBlock_id = "commentList{$this->_block_id}";
        $this->_commentBlock_id = "comments{$this->_block_id}";

        $this->_commentFormBlock = Html::tag('div', null, [
            'class' => 'f12-comment-form-block',
            'id' => $this->_commentFormBlock_id,
            'data-object_id' => $this->object_id,
            'data-classname' => $this->classname,
            'data-comments_list_id' => $this->_commentListBlock_id
        ]);

        $this->_commentListBlock = Html::tag('div', null, [
            'class' => 'f12-comment-list',
            'id' => $this->_commentListBlock_id,
            'data-object_id' => $this->object_id,
            'data-classname' => $this->classname,
        ]);
    }

    public function run()
    {
        if ($this->formPosition == Module::FORM_POSITION_BEFORE_LIST)
            $this->_html .= $this->_commentFormBlock;

        $this->_html .= $this->_commentListBlock;

        if ($this->formPosition == Module::FORM_POSITION_AFTER_LIST)
            $this->_html .= $this->_commentFormBlock;

        $formParams = json_encode([
            'block_id' => "#{$this->_commentFormBlock_id}",
            'classname' => $this->classname,
            'object_id' => $this->object_id,
        ]);

        $this->view->registerJs("f12Comments.indexUrl = '{$this->_f12CommentIndexUrl}'");
        $this->view->registerJs("f12Comments.formUrl = '{$this->_f12CommentFormUrl}'");
        $this->view->registerJs("f12Comments.deleteUrl = '{$this->_f12CommentDeleteUrl}'");
        $this->view->registerJs("f12Comments.updateUrl = '{$this->_f12CommentUpdateUrl}'");


        if ($this->showForm)
            $this->view->registerJs("f12Comments.loadForm({$formParams})", View::POS_READY, $this->_commentFormBlock_id);
        $this->view->registerJs("f12Comments.loadList('#{$this->_commentListBlock_id}')", View::POS_READY, $this->_commentListBlock_id);

        return Html::tag('div', $this->_html, ['class' => 'f12-comments', 'data-params' => $formParams]);

    }

}