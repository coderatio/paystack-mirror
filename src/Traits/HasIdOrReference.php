<?php


namespace Coderatio\PaystackMirror\Traits;


trait HasIdOrReference
{
    protected function getIdOrReference()
    {
        $data = [];

        if (is_int($this->data)) {
            $data['id'] = $this->data;
        }

        if (is_string($this->data)) {
            $data['reference'] = $this->data;
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        if (isset($this->data['reference'], $this->data['id'])) {
            $this->data['reference'] = null;
        }

        return $this->data['reference'] ?? $this->data['id'] ?? null;
    }
}
