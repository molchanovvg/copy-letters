<?php

namespace app\controllers;

use app\models\forms\CopyForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionCopy()
    {
        $copyForm = new CopyForm();

        if ($copyForm->load(Yii::$app->request->post())) {
            if ($copyForm->copyFiles()) {
                foreach ($copyForm->report as $item) {
                    Yii::$app->session->addFlash('success', $item);
                }
            } else {
                foreach ($copyForm->errors as $error) {
                    Yii::$app->session->addFlash('error', $error);
                }
            }
        }
        return $this->render('copy', [
            'copyForm' => $copyForm
        ]);
    }
}
