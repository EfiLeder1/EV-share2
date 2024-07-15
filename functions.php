<?php
    function baseUrl(){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }

        $subPath = "";
        if(strpos($_SERVER['HTTP_HOST'], "localhost") >= 0){
            $subPath = "/ev";
        }

        return $protocol . "://" . $_SERVER['HTTP_HOST'] . $subPath . "/";
    }

    function getTimeDifferenceInHrs($datetime_1, $datetime_2){
        $start_date = new DateTime($datetime_1);
        $since_start = $start_date->diff(new DateTime($datetime_2));

        return (float)$since_start->h.'.'.$since_start->i;
    }

    function getTimeDifferenceInMins($datetime_1, $datetime_2){
        $start_date = new DateTime($datetime_1);
        $since_start = $start_date->diff(new DateTime($datetime_2));

        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
        $minutes += $since_start->i;

        return $minutes;
    }
?>