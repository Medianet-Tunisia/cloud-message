<?php

namespace MedianetDev\CloudMessage\Contracts;

interface NotificationInterface
{
    public static function sendToAll(array $message);

    public static function sendToTopic(array $message, string $topic);

    public static function sendToTokens(array $message, array $tokens);

    public static function subscribeToTopic(string $topic, array $tokens);

    public static function unsubscribeToTopic(string $topic, array $tokens);

    // etc
}
