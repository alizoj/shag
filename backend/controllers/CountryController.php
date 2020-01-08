<?php

namespace backend\controllers;

use Yii;
use backend\models\Country;
use backend\models\CountrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class CountryController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Country models.
     *
     * @return mixed
     */
    public function actionIndex() {
        $searchModel  = new CountrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Country model.
     *
     * @param string $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate() {
        $model = new Country();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->code]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->code]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Country model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Country::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $code1
     * @param $code2
     *
     * @return string
     * @throws \Throwable
     */
    public function actionTest($code1, $code2) {

        Yii::$app->user->identity->id;

//        $row = Country::updateAll(['code' => $code2], ['code' => $code1]);
//
        try {
            $transaction = Yii::$app->db->beginTransaction();

            $countries = Country::find()->all();
            foreach ($countries as $country) {
                $country->population = rand(0, 1000000);
                if (rand(0, 2) === 1) {
                    $country->population = 1 / 0;
                }
                $country->save();
            }

            $transaction->commit();

            return "SUCCESS!";
        } catch (\Exception $e) {
            $transaction->rollBack();

            return;
        }
        //
        //        if ($row > 0) {
        //            return "SUCCESS!";
        //        }
        //        $country = Country::findOne(['code' => $code1]);
        //
        //        if ($country) {
        //            $country->code = $code2;
        //            if ($country->save()) {
        //                return "SUCCESS!";
        //            }
        //            var_dump($country->errors);
        //
        //            return "ERROR!";
        //        }

        //        return "NOT FOUND";
    }
}
