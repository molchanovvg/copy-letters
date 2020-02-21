<?php

namespace app\models\domains;


use app\models\Client;

/**
 * Class SearchItem
 * @package app\models\domains
 */
class SearchItem
{
    /** @var Client $client */
    public $client;

    /** @var ClientLetter $last */
    public $last;

    /** @var ClientLetter $prev */
    public $prev;


    /**
     * SearchItem constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->client = $config['client'];
        $this->last = new ClientLetter($config['lastFile'], $this->client);
        $this->prev = new ClientLetter($config['prevFile'], $this->client);
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->client->validate() && $this->last->validate() ;//;&& $this->prev->validate();
    }
}