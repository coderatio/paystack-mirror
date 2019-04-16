<?php


namespace Coderatio\PaystackMirror\Traits;


trait HasRecipientCodeOrId
{
    public function getRecipientCodeOrId()
    {
        $data = [];

        if (is_int($this->data)) {
            $data['id'] = $this->data;
        }

        if (is_string($this->data)) {
            $data['recipient_code'] = $this->data;
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        if (isset($this->data['recipient_code'], $this->data['id'])) {
            $this->data['recipient_code'] = null;
        }

        return $this->data['recipient_code'] ?? $this->data['id'] ?? null;
    }
}
