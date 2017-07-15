<?php


namespace Nassau\CartoonBattle\Services\Request;

use Symfony\Component\HttpFoundation\Response;

class CsvResponse extends Response
{
    private $handle;

    public function __construct($status = Response::HTTP_OK, array $headers = [])
    {
        $this->handle = fopen('php://memory', 'w');

        parent::__construct("", $status, $headers + ['Content-Type' => 'text/csv']);
    }

    public function pushRow(array $data)
    {
        fputcsv($this->handle, $data);
    }

    public function sendContent()
    {
        echo $this->getContent();
    }


    public function getContent()
    {
        fseek($this->handle, 0);

        return stream_get_contents($this->handle);
    }

}