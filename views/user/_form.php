<?php

use app\models\EarthCountries;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'show_email')->checkbox(['checked ' => ($model->show_email == 1) ? true : false]) ?>

    <hr/>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sex')->dropDownList(['Not Set' => 'Not Set', 'Male' => 'Male', 'Female' => 'Female']) ?>

    <?= $form->field($model, 'country_id')->dropDownList(
        ArrayHelper::map(EarthCountries::getCountriesList(), 'id', 'name_en'),
        [
            'label' => 'Country',
            'prompt' => 'Choose Country',
        ]
    ); ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'about')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic',
    ]) ?>

    <?= $form->field($model, 'timezone')->dropDownList($timeZonesList,
        ['prompt' => $model->searchOffsetByTimezone($timeZonesList, $model->timezone), 'timezone' => $model->timezone],
        ['options' =>
            [
                $model->timezone => ['selected' => true]
            ],
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
