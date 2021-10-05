<?php
function debug_to_console($data) {
    if (is_array($data))
        $data = implode(',', $data);

    echo "<script>console.log('Debug Objects: " . $data . "' );</script>";
}

function changeToReadAbleStr($data)
{
    if (is_array($data))
        $data = implode(',', $data);

    return $data;
}

$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp"));

# set session variable

$_SESSION['cityName'] = $geo['geoplugin_city'];
$_SESSION['countryCode'] = $geo['geoplugin_countryCode'];
?>