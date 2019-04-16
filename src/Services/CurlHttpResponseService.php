<?php


namespace Coderatio\PaystackMirror\Services;


class CurlHttpResponseService
{
    public $headers = [];
    public $statusCode;
    public $text = '';

    public function __construct($statusCode, $headers, $text)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->text = $text;
    }

    public function __toString()
    {
        return $this->text;
    }

    public function asJson(): string
    {
        header('Content-Type: application/json');

        return $this->text;
    }

    public function asObject()
    {
        return json_decode($this->text);
    }

    public function asArray(): array
    {
        return (array) $this->asObject();
    }
}
