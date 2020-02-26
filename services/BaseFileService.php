<?php


namespace app\services;


use app\helpers\MonthTitle;
use app\models\Client;
use app\models\Settings;

/**
 * Class BaseFileService
 * @package app\services
 */
class BaseFileService
{
    public const excludeDir = ['..', '.', '.DS_Store'];

    public CONST DEFAULT_SEARCH_DEPTH = 2;
    /* @var Settings $workDir */
    protected $workDir;

    /* @var Settings $workDir */
    protected $searchDepth;

    protected $report;
    protected $errors;

    public function __construct()
    {
        $this->workDir = Settings::find()->andWhere(['label' => Settings::WORK_DIR])->one();
        $searchDepthCustom = Settings::find()->andWhere(['label' => Settings::SEARCH_DEPTH])->one();
        $this->searchDepth = $searchDepthCustom !== null ? $searchDepthCustom->value : self::DEFAULT_SEARCH_DEPTH;
    }

    public function getReport()
    {
        return $this->report;
    }

    /**
     * @param $error
     */
    protected function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function clientWorkDir(Client $client): string
    {
        return $this->workDir->value . DIRECTORY_SEPARATOR . $client->title_dir;
    }

    protected function validateWorkDir(): bool
    {
        $validWorkDir = $this->workDir instanceof Settings && '' !== $this->workDir->value;
        if (!$validWorkDir) {
            $this->addError('Не задана рабочая директория');
            $validWorkDir = false;
        }
        if ($validWorkDir && !is_dir($this->workDir->value)) {
            $this->addError('Рабочая директория не существует: ' . $this->workDir->value);
            $validWorkDir = false;
        }
        return $validWorkDir;
    }

    protected function validateClientWorkDir(Client $client): bool
    {
        $validWorkDir = true;
        $clientWorkDir = $this->clientWorkDir($client);
        if (!is_dir($clientWorkDir)) {
            $this->addError('Рабочая директория клиента не существует: ' . $clientWorkDir);
            $validWorkDir = false;
        }
        return $validWorkDir;
    }

    /**
     * @param string $clientWorkDir
     * @return bool|string
     */
    protected function findLastFile(string $clientWorkDir)
    {
        // найти последний год
        $yearsDir = array_diff(scandir($clientWorkDir, SCANDIR_SORT_DESCENDING), self::excludeDir);
        if ($yearsDir === []) {
            $this->addError('В рабочем каталоге клиента нет каталогов по годам: ' . $clientWorkDir);
            return false;
        }
        // while todo
        $clientWorkDirWithYear = $clientWorkDir . DIRECTORY_SEPARATOR . array_shift($yearsDir);
        // найти последний месяц
        $monthsDir = array_diff(scandir($clientWorkDirWithYear, SCANDIR_SORT_DESCENDING), self::excludeDir);
        if ($monthsDir === []) {
            $this->addError('В рабочем каталоге клиента по годам нет каталогов по месяцам: ' . $clientWorkDirWithYear);
            return false;
        }

        $clientWorkDirWithYearMonth = $clientWorkDirWithYear . DIRECTORY_SEPARATOR . array_shift($monthsDir);

        // найти последний файл
        $fileList = array_diff(scandir($clientWorkDirWithYearMonth, SCANDIR_SORT_DESCENDING), self::excludeDir);
        if ($fileList === []) {
            $this->addError('В рабочем каталоге клиента по месяцам нет файлов писем: ' . $clientWorkDirWithYearMonth);
            return false;
        }

        // запомнить, как источник копирования
        $lastFile = array_shift($fileList);
        return $clientWorkDirWithYearMonth . DIRECTORY_SEPARATOR . $lastFile;
    }

    /**
     * @param string $clientWorkDir
     * @return array|bool
     */
    protected function findPrevFiles(string $clientWorkDir)
    {
        $lastFewFiles = [];

        // найти последний год
        $yearsDir = array_diff(scandir($clientWorkDir, SCANDIR_SORT_DESCENDING), self::excludeDir);
        if ($yearsDir === []) {
            $this->addError('В рабочем каталоге клиента нет каталогов по годам: ' . $clientWorkDir);
            return false;
        }

        while (count($lastFewFiles) <= $this->searchDepth && $yearsDir !== []) {

            $clientWorkDirWithYear = $clientWorkDir . DIRECTORY_SEPARATOR . array_shift($yearsDir);
            // список месяцев в директории года
            $monthsDir = array_diff(scandir($clientWorkDirWithYear, SCANDIR_SORT_DESCENDING), self::excludeDir);
            if ($monthsDir === []) {
                $this->addError('В рабочем каталоге клиента по годам нет каталогов по месяцам: ' . $clientWorkDirWithYear);
                return false;
            }
            while (count($lastFewFiles) <= $this->searchDepth && $monthsDir !== []) {
                $clientWorkDirWithYearMonth = $clientWorkDirWithYear . DIRECTORY_SEPARATOR . array_shift($monthsDir);

                // список файлов в текущей директории месяца
                $fileList = array_diff(scandir($clientWorkDirWithYearMonth, SCANDIR_SORT_DESCENDING), self::excludeDir);
                if ($fileList === []) {
                    $this->addError('В рабочем каталоге клиента по месяцам нет файлов писем: ' . $clientWorkDirWithYearMonth);
                    return false;
                }
                while (count($lastFewFiles) <= $this->searchDepth && $fileList !== []) {
                    $file = array_shift($fileList);
                    $lastFewFiles[] = $clientWorkDirWithYearMonth . DIRECTORY_SEPARATOR . $file;
                }
            }
        }

        return $lastFewFiles;
    }

    public function createClientDir(Client $client): bool
    {
        if ($this->validateWorkDir() && $client->validate()) {
            $path = $this->clientWorkDir($client);

            if (!file_exists($path)) {
                if (mkdir($path) && is_dir($path)) {
                    return true;
                }
                $this->addError('Не удалось создать директорию: ' . $path);
            }
        }
        return false;
    }

}