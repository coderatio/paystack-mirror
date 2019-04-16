<?php


namespace Coderatio\PaystackMirror\Events;


interface ActionEvent
{
    public static function validate($keys);
}