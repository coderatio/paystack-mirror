<?php


interface EventInterface
{
    public static function capture();
    public function thenListenOn($event);
    public function thenValidate($keyOrKeys);
    public function thenGetEvent();
    public function thenGetData($property = '');
    public function thenForwardTo($url, array $moreHeaders = [], $method = 'POST');
}