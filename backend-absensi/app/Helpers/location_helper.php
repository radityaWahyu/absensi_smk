<?php

if (!function_exists('get_distance')) {

    function get_distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $unit = 'kilometers')
    {
        $earthRadius = ($unit === 'miles') ? 3959 : 6371; // Earth radius in miles or kilometers

        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $c = atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return round($distance * 1000, 0);
    }
}

if (!function_exists('human_read_distance')) {

    function human_read_distance($distance)
    {
        return number_format($distance / 1000, 2) . ' Km';
    }
}
