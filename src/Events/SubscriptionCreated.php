<?php


namespace Coderatio\PaystackMirror\Events;


class SubscriptionCreated implements ActionEvent
{
    public static function validate($keys): Event
    {
        return Event::capture()->thenValidate($keys)
            ->thenListenOn('subscription.create');
    }
}