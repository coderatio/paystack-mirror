<?php


namespace Coderatio\PaystackMirror\Traits;


trait HasCardBin
{
    public function getCardBin()
    {
        $data = [];

        if (is_int($this->data)) {
            $data['bin'] = $this->data;
        }

        if (is_string($this->data)) {
            $data['bin'] = $this->data;
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        return $this->data['bin'] ?? null;
    }
}