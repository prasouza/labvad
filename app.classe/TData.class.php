<?php
function parseDateTime($string, $timezone=null) {
    $date = new DateTime(
        $string,
        $timezone ? $timezone : new DateTimeZone('UTC')
    );
    
    if ($timezone) {
        // If our timezone was ignored above, force it.
        $date->setTimezone($timezone);
    }
    
    return $date;
}