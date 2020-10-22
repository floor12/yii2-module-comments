<?php


namespace floor12\comments\widgets;


use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class StarRatingWidget extends InputWidget
{
    /** @var int */
    public $ratingMinValue = 1;
    /** @var int */
    public $ratingMaxValue = 5;
    /** @var string */
    public $mainCssClass = 'f12-rating-field';
    /** @var array */
    private $html;

    public function run()
    {
        $name = $this->model ? BaseHtml::getInputName($this->model, $this->attribute) : $this->name;
        $value = $this->model ? $this->model->{$this->attribute} : $this->value;
        for ($i = $this->ratingMinValue; $i <= $this->ratingMaxValue; $i++) {
            $this->html[] = Html::radio($name, $i === $value, ['value' => $i]);
        }
        return Html::tag('div', implode(PHP_EOL, $this->html), ['class' => $this->mainCssClass]);
    }

}
