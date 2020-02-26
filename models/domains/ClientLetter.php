<?php


namespace app\models\domains;


use app\models\Client;
use function strlen;

/**
 * Class ClientLetter
 * @package app\models\domains
 */
class ClientLetter
{
    /** @var string $path */
    public $path;

    /** @var string $date */
    public $date;

    /** @var string $flight */
    public $flight;

    /**
     * ClientLetter constructor.
     * @param $path
     * @param $client
     */
    public function __construct(string $path, Client $client)
    {
        $this->path = $path;
        $fileName = basename($path);
        $onlyDateFileName = str_replace($client->title_file . ' ', '', $fileName);
        $dateInFileName = substr($onlyDateFileName, 0, 8);
        $this->date = $dateInFileName;
        $this->flight = substr($onlyDateFileName, 8, strlen($onlyDateFileName));
    }

    public function validate(): bool
    {
        return file_exists($this->path) && '' !== $this->date && null !== $this->date;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->date . ' ' . $this->flight;
    }

}