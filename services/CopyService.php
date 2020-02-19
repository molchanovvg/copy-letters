<?php

namespace app\services;

use app\models\Client;
use app\models\Flights;
use app\models\forms\CopyForm;
use app\models\Settings;
use Cassandra\Set;

/**
 * Class CopyService
 * @package app\services
 */
class CopyService
{
    /* @var Client $client */
    private $client;

    /* @var Flights $flight */
    private $flight;

    /* @var string $flightDate */
    private $flightDate;

    /* @var Settings $workDir */
    private $workDir;

    private $report;
    private $errors;

    /* @var string $source */
    private $source;


    /**
     * CopyService constructor.
     * @param Client $client
     * @param Flights $flight
     * @param string $flightDate
     */
    public function __construct(Client $client, Flights $flight, string $flightDate)
    {
        $this->client = $client;
        $this->flight = $flight;
        $this->flightDate = $flightDate;

        $this->workDir = Settings::find()->andWhere(['label' => Settings::WORK_DIR])->one();
    }

    /**
     * @return bool
     */
    public function validateBeforeRun(): bool
    {
        $validWorkDir = $this->workDir instanceof Settings && '' !== $this->workDir->title;
        if (!$validWorkDir) {
            $this->addError('Не задана рабочая директория');
        }
        $validFlightDate = '' !== $this->flightDate;
        if (!$validWorkDir) {
            $this->addError('Не задана дата рейса');
        }

        return $validWorkDir && $validFlightDate;
    }

    public function getReport()
    {
        return $this->report;
    }

    /**
     * @param $error
     */
    private function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function copyFile(): bool
    {
        $pathToClientWorkDir = $this->workDir->title . DIRECTORY_SEPARATOR . $this->client->title_dir;

        // 1 этап - определить последний файл, для этого:
        $source = $this->findLastFile($pathToClientWorkDir);

        // 2 - этап создание пути для копирования
        $new = $this->defineNewPath();

        // скопировать !
        if ($this->copy($source, $new)) {
            $this->report[] = 'Скопирован ' . $new;
            return true;
        }
        return false;
    }


    private function findLastFile(string $clientWorkDir)
    {
        // проверить существование каталога клиента
        if (!is_dir($clientWorkDir)) {
            $this->addError('Каталог клиента не найден: ' . $clientWorkDir);
            return false;
        }

        // найти последний год
        $yearsDir = array_diff(scandir($clientWorkDir, SCANDIR_SORT_DESCENDING), ['..', '.']);
        if ($yearsDir === []) {
            $this->addError('В рабочем каталоге клиента нет файлов-источников');
            return false;
        }
        $clientWorkDirWithYear = $clientWorkDir . DIRECTORY_SEPARATOR . array_unshift($yearsDir);
        // найти последний месяц
        $monthsDir = array_diff(scandir($clientWorkDirWithYear, SCANDIR_SORT_DESCENDING), ['..', '.']);

        $clientWorkDirWithYearMonth = $clientWorkDirWithYear . DIRECTORY_SEPARATOR . array_unshift($monthsDir);

        // найти последний файл
        $fileList = array_diff(scandir($clientWorkDirWithYearMonth, SCANDIR_SORT_DESCENDING), ['..', '.']);


        foreach ($fileList as $file) {

        }


        // запомнить, как источник копирования
        return '123';
    }

    private function defineNewPath()
    {
        // проверить год

        // проверить месяц

        // сгенерить имя файла

        return '123';
    }

    private function copy($source, $new)
    {
        // copy
        return true;
    }


}