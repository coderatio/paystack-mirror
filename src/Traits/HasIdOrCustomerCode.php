<?php


namespace Coderatio\PaystackMirror\Traits;


trait HasIdOrCustomerCode
{
    public function getIdOrCustomerCode()
    {
        $data = [];

        if (is_int($this->data)) {
            $data['id'] = $this->data;
        }

        if (is_string($this->data)) {
            $data['customer_code'] = $this->data;
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        if (isset($this->data['customer_code'], $this->data['id'])) {
            $this->data['customer_code'] = null;
        }

        return $this->data['customer_code'] ?? $this->data['id'] ?? null;
    }
}
