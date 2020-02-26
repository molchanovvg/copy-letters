<?php

namespace app\services;

use app\helpers\ExcelCell;
use app\helpers\MonthTitle;
use app\models\Client;
use app\models\Flights;
use app\models\forms\CopyForm;
use app\models\Settings;
use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef;
use Yii;
use function is_array;

/**
 * Class CopyService
 * @package app\services
 */
class CopyService extends BaseFileService
{
    /* @var Client $client */
    private $client;

    /* @var Flights $flight */
    private $flight;

    /* @var string $flightDate */
    private $flightDate;


    /* @var string $pathToClientWorkDir */
    private $pathToClientWorkDir;



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

        parent::__construct();
    }

    public function setup(): bool
    {
        if ($this->validateBeforeRun()) {
            $this->pathToClientWorkDir = $this->clientWorkDir($this->client);
            if (is_dir($this->pathToClientWorkDir)) {
                return true;
            }
            $this->addError('Рабочая директория клиента не существует: ' . $this->pathToClientWorkDir);
        }
        return false;
    }
    /**
     * @return bool
     */
    public function validateBeforeRun(): bool
    {
        $validWorkDir = $this->validateWorkDir();

        $validFlightDate = '' !== $this->flightDate;
        if (!$validFlightDate) {
            $this->addError('Не задана дата рейса');
        }

        $validClient = null !== $this->client && $this->client->validate();
        if (!$validClient) {
            $this->addError('В клиенте не заданы поля');
        }

        return $validWorkDir && $validFlightDate && $validClient;
    }

    /**
     * @return bool
     */
    public function copyFile(): bool
    {
        $source = '';
        // 1 этап - определить последний файл, для этого:
        $searchResult = $this->findLastFile($this->pathToClientWorkDir);
        if (false === $searchResult) {
            $source = Yii::getAlias('@sources') . '/template.xlsx';
        }
        if (is_array($searchResult)) {
            $source = array_shift($searchResult);
        }

        $this->report[] = 'Источник: ' . $source;

        // 2 - этап создание пути для копирования
        $resultDefine = $this->defineNewPath($this->pathToClientWorkDir);
        if (false === $resultDefine) {
            return false;
        }

        $this->report[] = 'Сформирован ' . $resultDefine;

        // скопировать !
        if ($this->copy($source, $resultDefine)) {
            $this->report[] = 'Скопирован ' . $resultDefine;

            $this->editFile($resultDefine);
            return true;
        }
        return false;
    }

    /**
     * @param string $clientWorkDir
     * @return bool|string
     * @throws Exception
     */
    private function defineNewPath(string $clientWorkDir)
    {
        $date = new DateTime($this->flightDate);
        $yearPart = $date->format('Y') . ' г';
        $monthPart = $date->format('m') . '.' . $date->format('y') . ' (' . MonthTitle::get($date->format('m')) . ')';

        // проверить год
        $clientWorkDirWithYear = $clientWorkDir . DIRECTORY_SEPARATOR . $yearPart;
        if (!file_exists($clientWorkDirWithYear)) {
            if (!mkdir($clientWorkDirWithYear) && !is_dir($clientWorkDirWithYear)) {
                $this->addError('Не удалось создать директорию: ' . $clientWorkDirWithYear);
                return false;
            }
        }
        // проверить месяц
        $clientWorkDirWithYearMonth = $clientWorkDirWithYear . DIRECTORY_SEPARATOR . $monthPart;
        if (!file_exists($clientWorkDirWithYearMonth)) {
            if (!mkdir($clientWorkDirWithYearMonth) && !is_dir($clientWorkDirWithYearMonth)) {
                $this->addError('Не удалось создать директорию: ' . $clientWorkDirWithYearMonth);
                return false;
            }
        }

        // сгенерить имя файла
        $newFileName = $this->client->title_file . ' ' . $date->format('d.m.y') . ' ' . $this->flight->title . '.xlsx';

        $pathToNewFile = $clientWorkDirWithYearMonth . DIRECTORY_SEPARATOR . $newFileName;

        if (file_exists($pathToNewFile)) {
            $this->addError('Указанный файл существует: ' . $pathToNewFile);
            return false;
        }

        return $pathToNewFile;
    }


    /**
     * @param $source
     * @param $new
     * @return bool
     */
    private function copy($source, $new): bool
    {
        return copy($source, $new);
    }

    /**
     * @param $pathToFile
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function editFile($pathToFile): bool
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = null;
        if (file_exists($pathToFile)) {
            $spreadsheet = $reader->load($pathToFile);
        }
        if ($spreadsheet === null) {
            return false;
        }

        $cell = 'G1';// LookupRef::cellAddress(1, 7, 1, true); //'R1:C7';
        $spreadsheet->getActiveSheet()->setCellValue($cell, Yii::$app->formatter->asDate($this->flightDate) . ' (рейс № ' . $this->flight->title . ')');

        $cell = 'G3';// LookupRef::cellAddress(1, 7, 1, true); //'R1:C7';
        $spreadsheet->getActiveSheet()->setCellValue($cell, $this->client->header_in_file);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($pathToFile);

        return true;
    }


}