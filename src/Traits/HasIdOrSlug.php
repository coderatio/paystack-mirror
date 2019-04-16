<?php


namespace Coderatio\PaystackMirror\Traits;


trait HasIdOrSlug
{
    public function getIdOrSlug()
    {
        $data = [];

        if (is_int($this->data)) {
            $data['id'] = $this->data;
        }

        if (is_string($this->data)) {
            $data['slug'] = $this->data;
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        if (isset($this->data['slug'], $this->data['id'])) {
            $this->data['slug'] = null;
        }

        return $this->data['slug'] ?? $this->data['id'] ?? null;
    }
}
