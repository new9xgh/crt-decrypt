<?php
namespace frontend\controllers;


use frontend\models\DecryptForm;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class DecryptController extends Controller
{

    public function actionIndex()
    {
        $model = new DecryptForm();
        return $this->render('index', ['model' => $model]);
    }

    public function actionDecryptByConfig()
    {
        $model = new DecryptForm(['scenario' => DecryptForm::SCENARIO_DECRYPT_BY_CONFIG]);
        if (!Yii::$app->request->isPost) {
            return $this->redirect(['index']);
        }

        $model->uploadConfigFile();
        if (!$model->validate()) {
            \Yii::$app->getSession()->setFlash('error', Json::encode($model->getErrors()));
            return $this->redirect(['index']);
        }

        if (!$model->decryptByConfig()) {
            \Yii::$app->getSession()->setFlash('error', "解密失败！");
            return $this->redirect(['index']);
        }

        \Yii::$app->getSession()->setFlash('success', "解密成功！");
        return $this->render('result', ['model' => $model]);
    }

    public function actionDecryptByHashPassword()
    {
        $model = new DecryptForm(['scenario' => DecryptForm::SCENARIO_DECRYPT_BY_HASH_PASSWORD]);
        if (!Yii::$app->request->isPost) {
            return $this->redirect(['index']);
        }

        $model->load(Yii::$app->request->post());
        if (!$model->validate()) {
            \Yii::$app->getSession()->setFlash('error', Json::encode($model->getErrors()));
            return $this->redirect(['index']);
        }

        if (!$model->decryptByHash()) {
            \Yii::$app->getSession()->setFlash('error', "解密失败！");
            return $this->redirect(['index']);
        }

        \Yii::$app->getSession()->setFlash('success', "解密成功！");
        return $this->render('result', ['model' => $model]);
    }
}