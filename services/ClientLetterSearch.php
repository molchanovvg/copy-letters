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
                'lastFile' => $this->getLastFile($client),
                'prevFile' => $this->getPrevFile($client),
            ];
            $searchItem = new SearchItem($config);
            if ($searchItem->validate()) {
                $output[] = $searchItem;
            }

        }
        return $output;
    }

    protected function getLastFile(Client $client)
    {
        if ($this->validateWorkDir()) {
            $clientWorkDir = $this->clientWorkDir($client);
            return $this->findLastFile($clientWorkDir);
        }
        return false;
    }

    protected function getPrevFile(Client $client)
    {
        if ($this->validateWorkDir()) {
            $clientWorkDir = $this->clientWorkDir($client);
            return $this->findPrevFile($clientWorkDir);
        }
        return false;
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