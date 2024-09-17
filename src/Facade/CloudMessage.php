<?php

namespace MedianetDev\CloudMessage\Facade;

class CloudMessage
{
    public static function sendToAll(array $message, string $driver = 'firebase')
    {
        // Detect driver class
        $class = self::getDriverClass($driver);

        // Call driver method
        return $class::sendToAll($message);
    }

    public static function sendToTokens(array $message, array $tokens, string $driver = 'firebase')
    {
        // Detect driver class
        $class = self::getDriverClass($driver);

        // Call driver method
        return $class::sendToTokens($message, $tokens);
    }

    public static function sendToTopic(array $message, string $topic, string $driver = 'firebase')
    {
        // Detect driver class
        $class = self::getDriverClass($driver);

        // Call driver method
        return $class::sendToTopic($message, $topic);
    }

    public static function subscribeToTopic(string $topic, array $tokens, string $driver = 'firebase')
    {
        // Detect driver class
        $class = self::getDriverClass($driver);

        // Call driver method
        return $class::subscribeToTopic($topic, $tokens);
    }

    public static function unsubscribeToTopic(string $topic, array $tokens, string $driver = 'firebase')
    {
        // Detect driver class
        $class = self::getDriverClass($driver);

        // Call driver method
        return $class::unsubscribeToTopic($topic, $tokens);
    }

    protected static function getDriverClass(string $driver)
    {
        $drivers = [
            'firebase' => 'MedianetDev\CloudMessage\Drivers\FirebaseNotification',
            'huawei' => 'MedianetDev\CloudMessage\Drivers\HuaweiNotification',
        ];

        if (! array_key_exists($driver, $drivers)) {
            throw new \Exception('Driver not found');
        }

        return $drivers[$driver];
    }
}
