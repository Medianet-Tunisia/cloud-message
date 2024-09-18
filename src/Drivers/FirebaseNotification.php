<?php

namespace MedianetDev\CloudMessage\Drivers;

use Google\Client;
use MedianetDev\CloudMessage\Contracts\NotificationInterface;
use MedianetDev\CloudMessage\Jobs\MultiTokensJob;

class FirebaseNotification implements NotificationInterface
{
    use Notification;

    private static $firebaseApiBaseUrl = 'https://fcm.googleapis.com/v1/projects/';
    private static $firebaseMessagingScope = 'https://www.googleapis.com/auth/firebase.messaging';
    private static $googleApiBaseUrl = 'https://iid.googleapis.com/iid/v1/';

    public static function sendToAll(array $message)
    {
        // TODO: Send to all devices
    }

    public static function sendToTopic(array $message, $topic)
    {
        $headers = [
            'Authorization: Bearer '.self::getAccessToken(),
            'Content-Type: application/json',
        ];

        $url = self::$firebaseApiBaseUrl.config('cloud_message.firebase.project_id').'/messages:send';

        $data = [
            'message' => [
                'topic' => $topic,
                'notification' => $message,
            ],
        ];

        $response = self::request($url, json_encode($data), $headers);

        return ['status' => $response['status']];
    }

    public static function sendToTokens(array $message, array $tokens)
    {
        $url = self::$firebaseApiBaseUrl.config('cloud_message.firebase.project_id').'/messages:send';
        try {
            $headers = [
                'Authorization: Bearer '.self::getAccessToken(),
                'Content-Type: application/json',
            ];
            if (config('cloud_message.async_requests')) {
                dispatch(new MultiTokensJob($tokens, $message, $url, $headers));
            } else {
                foreach ($tokens as $mobileId) {
                    self::request($url, json_encode(['message' => [
                        'token' => $mobileId,
                        'notification' => $message,
                    ]]), $headers);
                }
            }

            return [
                'status' => true,
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
            ];
        }
    }

    public static function subscribeToTopic(string $topic, array $tokens)
    {
        $url = self::$googleApiBaseUrl.':batchAdd';

        $headers = [
            'Authorization: Bearer '.self::getAccessToken(),
            'Content-Type: application/json',
            'access_token_auth: true',
        ];

        $data = [
            'to' => '/topics/'.$topic,
            'registration_tokens' => $tokens,
        ];

        $response = self::request($url, json_encode($data), $headers);
        $statusCode = $response['status'];

        return [
            'status' => 200 == $statusCode,
        ];
    }

    public static function unsubscribeToTopic(string $topic, array $tokens)
    {
        $url = self::$googleApiBaseUrl.':batchRemove';

        $headers = [
            'Authorization: Bearer '.self::getAccessToken(),
            'Content-Type: application/json',
            'access_token_auth: true',
        ];

        $data = [
            'to' => '/topics/'.$topic,
            'registration_tokens' => $tokens,
        ];

        $response = self::request($url, json_encode($data), $headers);
        $statusCode = $response['status'];

        return [
            'status' => 200 == $statusCode,
        ];
    }

    // Other required interface methods
    private static function getAccessToken()
    {
        $client = new Client();
        $client->setAuthConfig(config('cloud_message.firebase.path_to_service_account'));
        $client->addScope(self::$firebaseMessagingScope);
        $client->useApplicationDefaultCredentials();
        $token = $client->fetchAccessTokenWithAssertion();

        return $token['access_token'] ?? '';
    }
}
