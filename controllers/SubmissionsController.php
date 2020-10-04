<?php

namespace app\controllers;

use app\common\Helper;
use Yii;
use app\models\activerecord\Submissions;
use app\base\controllers\ProtectedController;
use app\models\search\SubmissionsSearch;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii2tech\csvgrid\CsvGrid;

/**
 * SubmissionsController implements the CRUD actions for Submissions model.
 */
class SubmissionsController extends ProtectedController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Submissions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubmissionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Submissions model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionExport(){
        $submissions = Submissions::find()->all();
        $model = new Submissions();
        $columns = $fileValues = [];

        $attributes = $model->getAttributes();
        $exportableColumns = $model->exportableColumns;

        foreach ($attributes as $attribute=>$v){
            if(in_array($attribute, $exportableColumns)) {
                $columns[] = array(
                    'attribute' => Helper::fixUnderScores($attribute),
                    'format' => 'raw',
                );
            }
        }
        foreach ($submissions as $submission) {
            $fileValuesRow = [];
            $attributes = $submission->attributes;
            foreach ($attributes as $attribute=>$value){
                if(in_array($attribute, $exportableColumns)) {
                    $fileValuesRow[Helper::fixUnderScores($attribute)] = $value;
                }
            }
            $fileValues[] = $fileValuesRow;
        }

        $exporter = new CsvGrid([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $fileValues,
            ]),
            'columns' => $columns,
        ]);

        return $exporter->export()->send('submission-report-'.time().'.csv');

    }


    /**
     * Deletes an existing Submissions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Submissions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Submissions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Submissions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
