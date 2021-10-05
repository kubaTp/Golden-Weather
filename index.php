<?php
session_start();
error_reporting(0);
include('city.php');

$_SESSION['weather'] = 'def';

# simple html

print<<<EOF
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Golden weather</title>
        <link rel="stylesheet" href="main.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <script type="text/javascript" src="background_shift.js"></script>
        <script type="text/javascript" src="extra_func.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="app-wrap">
            <header>
            <form method="POST">
                <input type="text" autocomplete="off" class="search-box" name="search-box" placeholder="Search for a city...">
            </form>
            </header>
            <main>
                <section class="location">
                    <p id="error_msg"> wrong city name!</p>
                    <div class="city">
                        {$_SESSION['cityName']}, {$_SESSION['countryCode']}
                    </div>
                    <div class="date">
                        Monday 25  November 2020
                    </div>
                </section>
                <div class="current">
                    <div class="temp">
                        15<span>°c</span>
                    </div>
                    <div class="weather">
                        Sunny
                    </div>
                    <div class="hi-low">13°c / 18°c</div>
                </div>
            </main>
        </div>
    </body>
</html>
EOF;

# API request and get data

if(isset($_POST['search-box']))
    $data = $_POST['search-box'];
else
    $data = $_SESSION['cityName'];

$url = "https://api.openweathermap.org/data/2.5/weather?q=$data&units=metric&appid=04973cc9cf3eab7bf7c06fe5a5375022&fmt=json3";

$data = file_get_contents($url);
$data = json_decode($data, 1);

if(!$data)
{
    print<<<EOF
    <script>
        document.getElementById("error_msg").style.display = "inline-block";
    </script>
EOF;
}
else
{
    # set variables
    $date = getdate();

    $temp[] = round($data['main']['temp'], 0);
    $temp[] = round($data['main']['temp_min'], 0);
    $temp[] = round($data['main']['temp_max'], 0);

    $_SESSION['weather'] = $data['weather'][0]['main'];

    print<<<EOF
    <script>
    document.querySelector('.location .city').innerText = "{$data['name']}, {$data['sys']['country']}";
    document.querySelector('.date').innerText = "{$date['weekday']} {$date['mday']} {$date['month']}";
    document.querySelector('.current .temp').innerHTML = `$temp[0]<span>°c</span>`;
    document.querySelector('.current .weather').innerText = "{$data['weather'][0]['main']}";
    document.querySelector('.current .hi-low').innerText = "{$temp[1]}°c / {$temp[2]}°c";
    </script>
EOF;
}
?>