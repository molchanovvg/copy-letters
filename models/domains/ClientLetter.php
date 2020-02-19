<?php


namespace app\models\domains;


use app\models\Client;

/**
 * Class ClientLetter
 * @package app\models\domains
 */
class ClientLetter
{
    public $date;
    /** @var Client $client */
    public $client;
    public $path;

    /**
     * ClientLetter constructor.
     * @param $date
     * @param $client
     * @param $path
     */
    public function __construct($date, $client, $path)
    {
        $this->date = $date;
        $this->client = $client;
        $this->path = $path;
    }
}