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
