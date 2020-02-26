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

    /** @var ClientLetter[] $last */
    public $lastFiles;

    /**
     * SearchItem constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->client = $config['client'];
        if (array_key_exists('lastFiles', $config) && is_array($config['lastFiles'])) {
            foreach ($config['lastFiles'] as $file) {
                $this->lastFiles[] = new ClientLetter($file, $this->client);
            }
        }
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $validLastFiles = true;
        if (is_array($this->lastFiles)) {
            foreach ($this->lastFiles as $lastFile) {
                $validLastFiles = $validLastFiles && $lastFile->validate();
            }
        } else {
            $validLastFiles = false;
        }

        return $this->client->validate() && $validLastFiles;
    }
}