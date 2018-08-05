<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29.07.2018
 * Time: 21:09
 *
 * @var $this \yii\web\View
 * @var $model \common\models\Comment
 *
 */

use kartik\form\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'floor12-comments-form'
]);

echo $form->field($model, 'content')->textarea()->label(false);

ActiveForm::end();