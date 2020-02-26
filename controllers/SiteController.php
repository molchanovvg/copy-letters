<?php

namespace app\controllers;

use app\models\forms\CopyForm;
use app\services\ClientLetterSearch;
use Yii;
use yii\data\ArrayDataProvider;
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
    public function actionCopy(): string
    {
        $copyForm = new CopyForm();

        if ($copyForm->load(Yii::$app->request->post())) {
            if ($copyForm->runService()) {
                foreach ($copyForm->report as $item) {
                    Yii::$app->session->addFlash('success', $item);
                }
            } else {
                foreach ($copyForm->errors as $error) {
                    Yii::$app->session->addFlash('error', $error);
                }
            }
        }
        $search = new ClientLetterSearch();
        $allModels = $search->getLastPrevLetterList();
        $arrayDataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
        ]);
        if ($search->hasErrors()) {
            Yii::$app->session->addFlash('error', $search->errors);
        }
        return $this->render('copy', [
            'copyForm' => $copyForm,
            'dataProvider' => $arrayDataProvider,
        ]);
    }

    public function actionOpenFile()
    {
        $prepareFileToOpen = '';
        $post = Yii::$app->request->post();
        if (array_key_exists('file', $post) && $post['file'] !== null && $post['file'] !== '') {
            $prepareFileToOpen = Yii::$app->request->post('file');
        }


        if (file_exists($prepareFileToOpen)) {

            if (stripos(PHP_OS, 'win') === 0) {
                $prepareFileToOpen = '"' . $prepareFileToOpen . '"';
            }

            $config = [
                0 => ['pipe', 'r'],  // stdin - канал, из которого дочерний процесс будет читать
                1 => ['pipe', 'w'],  // stdout - канал, в который дочерний процесс будет записывать
                2 => ['file', sys_get_temp_dir() . '/error-output.txt', 'a'] // stderr - файл для записи
            ];
            $process = proc_open($prepareFileToOpen, $config, $pipes, null, null, ['bypass_shell']);
            return '<script>window.close()</script>';
        }
        Yii::$app->session->addFlash('error', 'Указанный файл не существует: ' . $prepareFileToOpen);
        return $this->redirect(['site/copy']);
    }
}
