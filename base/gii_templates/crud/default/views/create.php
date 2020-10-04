<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */
echo "<?php\n";
?>

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('Create ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
?>

<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=Url::toRoute(['user/index'])?>">Dashboard</a></li>
        <li class="breadcrumb-item">
            <?= "<?= " ?>Html::a(<?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, ['index'], ['class' => '']) ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= "<?= " ?>Html::encode($this->title) ?></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <?= "<?= " ?>Html::a(<?= $generator->generateString('Go Back') ?>, ['index'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= "<?= " ?>Html::encode($this->title) ?></h3>
            </div>
            <div class="card-body">
                <?= "<?= " ?>$this->render('_form', [
                'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>

</div>