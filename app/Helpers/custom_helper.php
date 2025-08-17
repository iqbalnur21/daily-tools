<?php
function timeFormat($timestamp)
{
    $time = strtotime($timestamp);
    $date = date('Y-m-d', $time);
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));

    $timePart = date('H:i:s', $time);

    if ($date === $today) {
        return "Hari Ini $timePart";
    } elseif ($date === $yesterday) {
        return "Kemarin $timePart";
    } else {
        return date('Y-m-d H:i:s', $time);
    }
}

function log_activity(string $username)
{
    {
        $url = "https://balrafa-api.vercel.app/api/log?token=" . env('TOKEN_LOG');

        $data = ['user' => $username. ' from daily tools system'];

        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
                'timeout' => 10,
            ],
        ];

        $context  = stream_context_create($options);
        $result   = file_get_contents($url, false, $context);

        if ($result === false) {
            return ['error' => 'Failed to log activity'];
        }

        return json_decode($result, true);
    }
}