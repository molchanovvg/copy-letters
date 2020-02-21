<?php


namespace app\models\domains;


class ClientLetter
{
    public $path;
    public $date;

    public function __construct($path, $client)
    {
        $this->path = $path;
        $fileName = basename($path);
        $onlyDateFileName = str_replace($client->title_file . ' ', '', $fileName);
        $dateInFileName = substr($onlyDateFileName, 0, 8);
        $this->date = $dateInFileName;
    }

    public function validate(): bool
    {
        return is_dir($this->path) && $this->date !== '' && $this->date !== null;
    }
}