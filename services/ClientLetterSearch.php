<?php


namespace app\services;


use app\models\Client;
use app\models\domains\SearchItem;
use app\models\Settings;
use DateTime;
use function is_array;

/**
 * Class ClientLetterSearch
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

    /**
     * @param Client $client
     * @return bool|string
     */
    protected function getLastFile(Client $client)
    {
        if ($this->validateWorkDir()) {
            $clientWorkDir = $this->clientWorkDir($client);
            return $this->findLastFile($clientWorkDir);
        }
        return false;
    }

    /**
     * @param Client $client
     * @return bool|void
     */
    protected function getPrevFile(Client $client)
    {
        if ($this->validateWorkDir()) {
            $clientWorkDir = $this->clientWorkDir($client);
            return $this->findPrevFile($clientWorkDir);
        }
        return false;
    }
}