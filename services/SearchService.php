<?php


namespace app\services;


use app\models\Client;
use app\models\domains\ClientLetter;
use app\models\Settings;
use DateTime;
use function is_array;

/**
 * Class SearchService
 * @package app\services
 */
class SearchService
{
    public $workDir;

    private $year;
    private $month;

    public function __construct($date)
    {
        $date = new DateTime($date);
        $this->year = $date->format('Y');
        $this->month = $date->format('m');
        $this->workDir = Settings::find()->andWhere(['label' => Settings::WORK_DIR])->one();
    }

    /**
     * @return array
     */
    public function findByDate(): array
    {
        $output = [];
        $clientList = Client::find()->andWhere(['is_active' => 1])->all();
        foreach ($clientList as $client) {

            $matchByNameList = $this->search($client);

            if (is_array($matchByNameList)) {
                foreach ($matchByNameList as $path) {
                    $output[] = new ClientLetter($date, $client, $path);
                }
            }
        }
        return $output;
    }

    /**
     * @param Client $client
     * @return array|false
     */
    private function search(Client $client)
    {
        $yearPart = $this->year;
        $monthPart = $this->month;
        $filename = $client->title_file;
        $date = $this->month . '.' . $this->year;
        $matchPattern = $this->workDir . DIRECTORY_SEPARATOR . $yearPart . DIRECTORY_SEPARATOR . $monthPart . DIRECTORY_SEPARATOR . $filename . '*' . $date;
        return glob($matchPattern);
    }
}