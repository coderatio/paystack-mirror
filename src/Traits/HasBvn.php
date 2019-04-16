<?php


namespace Coderatio\PaystackMirror\Traits;


trait HasBvn
{
    public function getBvn()
    {
        $data = [];

        if (is_int($this->data)) {
            $data['bvn'] = $this->data;
        }

        if (is_string($this->data)) {
            $data['bvn'] = $this->data;
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        return $this->data['bvn'] ?? null;
    }
}