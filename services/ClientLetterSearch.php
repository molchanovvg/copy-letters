<?php


namespace app\services;


use app\models\Client;
use app\models\domains\SearchItem;
use app\models\Settings;
use DateTime;
use function is_array;

/**
 * Class SearchService
 * @package app\services
 */
class ClientLetterSearch extends BaseFileService
{
    public $errors = [];
    /**
     * @return array
     */
    public function getLastPrevLetterList(): array
    {
        $output = [];
        $clientList = Client::find()->andWhere(['is_active' => 1])->all();
        foreach ($clientList as $client) {
            $config = [
                'client' => $client,
                'lastFiles' => $this->getPrevFiles($client),
            ];
            $searchItem = new SearchItem($config);
            if ($searchItem->validate()) {
                $output[] = $searchItem;
            }
        }
        return $output;
    }
//
//    protected function getLastFiles(Client $client)
//    {
//        if ($this->validateWorkDir() && $this->validateClientWorkDir($client)) {
//            $clientWorkDir = $this->clientWorkDir($client);
//            return $this->findLastFile($clientWorkDir);
//        }
//        $this->errors = array_merge($this->getErrors());
//        return false;
//    }

    protected function getPrevFiles(Client $client)
    {
        if ($this->validateWorkDir() && $this->validateClientWorkDir($client)) {
            $clientWorkDir = $this->clientWorkDir($client);
            return $this->findPrevFiles($clientWorkDir);
        }
        $this->errors = array_merge($this->getErrors());
        return false;
    }

    public function hasErrors(): bool
    {
        return $this->errors !== [];
    }

//    /**
//     * @param Client $client
//     * @return array|false
//     */
//    private function searchOld(Client $client)
//    {
//        $yearPart = $this->year;
//        $monthPart = $this->month;
//        $filename = $client->title_file;
//        $date = $this->month . '.' . $this->year;
//        $matchPattern = $this->workDir . DIRECTORY_SEPARATOR . $yearPart . DIRECTORY_SEPARATOR . $monthPart . DIRECTORY_SEPARATOR . $filename . '*' . $date;
//        return glob($matchPattern);
//    }
}