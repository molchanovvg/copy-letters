<?php


namespace app\services;


use app\helpers\MonthTitle;
use app\models\Client;
use app\models\Settings;

class BaseFileService
{
    /* @var Settings $workDir */
    protected $workDir;
    protected $report;
    protected $errors;

    public function __construct()
    {
        $this->workDir = Settings::find()->andWhere(['label' => Settings::WORK_DIR])->one();
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
        return $this->workDir . DIRECTORY_SEPARATOR . $client->title_dir;
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

    protected function findLastFile(string $clientWorkDir)
    {
        // найти последний год
        $yearsDir = array_diff(scandir($clientWorkDir, SCANDIR_SORT_DESCENDING), ['..', '.']);
        if ($yearsDir === []) {
            $this->addError('В рабочем каталоге клиента нет каталогов по годам: ' . $clientWorkDir);
            return false;
        }
        // while todo
        $clientWorkDirWithYear = $clientWorkDir . DIRECTORY_SEPARATOR . array_shift($yearsDir);
        // найти последний месяц
        $monthsDir = array_diff(scandir($clientWorkDirWithYear, SCANDIR_SORT_DESCENDING), ['..', '.']);
        if ($monthsDir === []) {
            $this->addError('В рабочем каталоге клиента по годам нет каталогов по месяцам: ' . $clientWorkDirWithYear);
            return false;
        }

        $clientWorkDirWithYearMonth = $clientWorkDirWithYear . DIRECTORY_SEPARATOR . array_shift($monthsDir);

        // найти последний файл
        $fileList = array_diff(scandir($clientWorkDirWithYearMonth, SCANDIR_SORT_DESCENDING), ['..', '.']);
        if ($fileList === []) {
            $this->addError('В рабочем каталоге клиента по месяцам нет файлов писем: ' . $clientWorkDirWithYearMonth);
            return false;
        }

        // запомнить, как источник копирования
        return $clientWorkDirWithYearMonth . DIRECTORY_SEPARATOR . array_shift($fileList);
    }

    protected function findPrevFile()
    {

    }

}