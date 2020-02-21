<?php

namespace app\services;

use app\helpers\MonthTitle;
use app\models\Client;
use app\models\Flights;
use app\models\forms\CopyForm;
use app\models\Settings;
use DateTime;

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
        // 1 этап - определить последний файл, для этого:
        $source = $this->findLastFile($this->pathToClientWorkDir);
        $this->report[] = 'Найден ' . $source;

        // 2 - этап создание пути для копирования
        $new = $this->defineNewPath($this->pathToClientWorkDir);
        $this->report[] = 'Сформирован ' . $new;

        // скопировать !
        if ($this->copy($source, $new)) {
            $this->report[] = 'Скопирован ' . $new;
            return true;
        }
        return false;
    }

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

        return $clientWorkDirWithYearMonth . DIRECTORY_SEPARATOR . $newFileName;
    }


    private function copy($source, $new): bool
    {
        return copy($source, $new);
    }


}