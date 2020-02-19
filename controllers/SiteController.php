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
        return $this->render('copy', [
            'copyForm' => $copyForm
        ]);
    }

    public function actionTest()
    {
        $path = "C:\\test\\Сургут\\Иванов.xlsx";
        $pathToExcel = "C:\\Program Files\\Microsoft Office 15\\root\\office15\\excel.exe -C:\test\Сургут\Иванов.xlsx";
//        $STDOUT = fopen('application.log', 'wb');
//        $STDERR = fopen('error.log', 'wb');
       // passthru(escapeshellcmd($path));


        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin - канал, из которого дочерний процесс будет читать
            1 => array("pipe", "w"),  // stdout - канал, в который дочерний процесс будет записывать
            2 => array("file", sys_get_temp_dir() . "/error-output.txt", "a") // stderr - файл для записи
        );

        $cwd = sys_get_temp_dir();
        $env = array('some_option' => 'aeiou');

        $process = proc_open($path, $descriptorspec, $pipes, null, null, ['bypass_shell']);
////
//        var_dump($process);
       // echo exec($path);
    }
}
