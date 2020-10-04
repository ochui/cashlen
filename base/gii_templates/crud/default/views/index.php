<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
?>

<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=Url::toRoute(['user/index'])?>">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= "<?= " ?>Html::encode($this->title) ?></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <?= "<?= " ?>Html::a(<?= $generator->generateString('Create New') ?>, ['create'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= "<?= " ?>Html::encode($this->title) ?></h3>
            </div>
            <div class="table-responsive">
                <?= $generator->enablePjax ? "    <?php Pjax::begin(); ?>\n" : '' ?>
                <?php if(!empty($generator->searchModelClass)): ?>
                    <?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
                <?php endif; ?>

                <?php
                $tableLayout = 'Yii::$app->params["gridViewTemplate"]';
                if ($generator->indexWidgetType === 'grid'): ?>
                    <?= "<?= " ?>GridView::widget([

                    'dataProvider' => $dataProvider,
                    <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n'layout'=> $tableLayout,\n 'tableOptions' => [ 'class'=>'table'],\n 'columns' => [\n" : "'columns' => [\n"; ?>
                    ['class' => 'yii\grid\SerialColumn'],

                    <?php
                    $count = 0;
                    if (($tableSchema = $generator->getTableSchema()) === false) {
                        foreach ($generator->getColumnNames() as $name) {
                            if (++$count < 6) {
                                echo "            '" . $name . "',\n";
                            } else {
                                echo "            //'" . $name . "',\n";
                            }
                        }
                    } else {
                        foreach ($tableSchema->columns as $column) {
                            $format = $generator->generateColumnFormat($column);
                            if (++$count < 6) {
                                echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                            } else {
                                echo "            //'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                            }
                        }
                    }
                    ?>

                    [
                    'header'=> 'Actions',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                    'view' => function ($url) {
                    return Html::a(
                    '<span class="ti-eye"></span>',
                                                 $url,
                                                 [
                                                 'title'=> 'View',
                                                 'data-pjax' => '0',
                                                 ]
                                                 );
                                                 },
                                                 'update' => function ($url) {
                                                 return Html::a(
                                                 '<span class="ti-pencil-alt "></span>',
                                                                                      $url,
                                                                                      [
                                                                                      'title'=> 'Edit',
                                                                                      'data-pjax' => '1',
                                                                                      ]
                                                                                      );
                                                                                      },
                                                                                      'delete' => function ($url) {
                                                                                      return Html::a(
                                                                                      '<span class="ti-trash "></span>',
                    $url,
                    [
                    'title'=> 'Delete',
                    'data-pjax' => '2',
                    'data-confirm' => 'Are you sure you want to delete this item?',
                    'data-method'  => 'post',
                    ]
                    );
                    }
                    ],
                    ],

                    ],
                    ]); ?>
                <?php else: ?>
                    <?= "<?= " ?>ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemOptions' => ['class' => 'item'],
                    'itemView' => function ($model, $key, $index, $widget) {
                    return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
                    },
                    ]) ?>
                <?php endif; ?>

                <?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : '' ?>
            </div>
        </div>
    </div>

</div>