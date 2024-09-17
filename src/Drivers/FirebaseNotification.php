<?php

namespace MedianetDev\CloudMessage\Drivers;

use Google\Client;
use MedianetDev\CloudMessage\Contracts\NotificationInterface;

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
        $notifications_data = [
            'count_of_tokens' => count($tokens),
            'count_success' => 0,
            'count_failed' => 0,
        ];

        $url = self::$firebaseApiBaseUrl.config('cloud_message.firebase.project_id').'/messages:send';
        try {
            $headers = [
                'Authorization: Bearer '.self::getAccessToken(),
                'Content-Type: application/json',
            ];

            foreach ($tokens as $mobileId) {
                $response = self::request($url, json_encode(['message' => [
                    'token' => $mobileId,
                    'notification' => $message,
                ]]), $headers);

                $data = json_decode($response['data'], true);

                if (false == $response || ($data['error'] ?? false)) {
                    ++$notifications_data['count_failed'];
                } else {
                    ++$notifications_data['count_success'];
                }
            }

            return [
                'status' => true,
                'data' => $notifications_data,
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'data' => $notifications_data,
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
