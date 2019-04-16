<?php


namespace Coderatio\PaystackMirror\Traits;


trait HasIdOrSubscriptionCode
{
    public function getIdOrSubscriptionCode()
    {
        $data = [];

        if (is_int($this->data)) {
            $data['id'] = $this->data;
        }

        if (is_string($this->data)) {
            $data['subscription_code'] = $this->data;
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        if (isset($this->data['subscription_code'], $this->data['id'])) {
            $this->data['subscription_code'] = null;
        }

        return $this->data['subscription_code'] ?? $this->data['id'] ?? null;
    }
}
