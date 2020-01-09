<?php

namespace backend\controllers;

use Yii;
use backend\models\Country;
use backend\models\CountrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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
     * @throws \yii\db\Exception
     */
    public function actionCreate() {

        $model = new Country();

        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();

            if ($model->save()) {

                $model->file = UploadedFile::getInstance($model, 'file');
                if ($model->file) {

                    if ($model->validate()) {
                        $model->file_name = $model->code . '_' . $model->file->baseName .
                                            '.' . $model->file->extension;//KZ_image.jpg
                        $model->save();

                        $file_upload_result = $model->file->saveAs(Yii::getAlias('@uploads') .
                                                                   '/country/' . $model->file_name);

                        if (!$file_upload_result) {
                            $transaction->rollBack();

                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    } else {
                        $transaction->rollBack();

                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                }

                $transaction->commit();

                return $this->redirect(['view', 'id' => $model->code]);
            } else {
                $transaction->rollBack();
            }
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
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id) {

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();

            if ($model->save()) {

                $model->file = UploadedFile::getInstance($model, 'file');

                if ($model->file) {

                    $upload_dir = Yii::getAlias('@uploads') . '/country/';

                    if ($model->validate()) {

                        $old_file_path = $upload_dir . $model->file_name;

                        $new_file_name = $model->code . '_' . $model->file->baseName . '.' .
                                         $model->file->extension;

                        $file_upload_result = $model->file->saveAs($upload_dir . $new_file_name);

                        if (!$file_upload_result) {
                            $transaction->rollBack();

                            return $this->render('update', [
                                'model' => $model,
                            ]);
                        } else {
                            $model->updateAttributes(['file_name' => $new_file_name]);
                            @unlink($old_file_path);
                        }
                    } else {
                        $transaction->rollBack();

                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }
            }

            $transaction->commit();

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


    /**
     * @param $file_name
     *                  yii2/country/get-image?file_name=asdasda
     *
     * @return bool|\yii\console\Response|\yii\web\Response
     */
    public function actionGetImage($file_name) {

        $base_path = Yii::getAlias('@uploads') . '/country/';

        if (file_exists($base_path . $file_name)) {
            return Yii::$app->response->sendFile($base_path . $file_name);
        }

        return false;
    }
}
