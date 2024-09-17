<?php

namespace MedianetDev\CloudMessage\Drivers;

trait Notification
{
    protected static function request(string $url, string $payload, array $headers = [], $method = 'POST')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $data = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (config('cloud_message.with_log')) {
            app('log')->debug(
                "\n------------------- Gateway request --------------------".
                    "\n#Url: ".$url.
                    "\n#Method: ".$method.
                    "\n#Headers: ".json_encode($headers).
                    "\n#Data: ".json_encode($payload).
                    "\n------------------- Gateway response -------------------".
                    "\n#Status code: ".$statusCode.
                    "\n#Response: ".json_encode($data).
                    "\n--------------------------------------------------------"
            );
        }

        return [
            'data' => $data,
            'status' => $statusCode == 200
        ];
    }
}
