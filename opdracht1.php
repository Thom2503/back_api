<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Weahter API</title>
    <style media="screen">
      @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap');
      * {
        font-family: 'Josefin Sans', sans-serif;
      }
      body {
        background-image: url(img/weather-clip-art.jpg);
        background-repeat: repeat;
        overflow: hidden;
      }
      h1 {
        text-align: center;
      }
      .info {
        position: relative;
        width: 80%;
        border: 1px solid black;
        margin: 0 auto;
        background-color: #fdf6e3;
      }
      .binneninfo, .binnenform {
        position: relative;
        left: 25%;
        margin: 15px;
      }
      .form {
        position: relative;
        width: 80%;
        border: 1px solid black;
        margin: 0 auto;
        background-color: #fdf6e3;
      }
    </style>
  </head>
  <body>
    <h1>Openweather API || Hoe is het weer bij jou?</h1>
    <form class="form" action="" method="post">
      <div class="binnenform">
        <h2>Waar ben je?</h2><br>
        <input type="text" name="plaats" placeholder="Rotterdam">
        <input type="submit" name="submit" value="Zoeken">
      </div>
    </form>
    <div class="info">
      <div class="binneninfo">
        <?php
            $url = "";
            $urlForecast = "";

            if (isset($_POST['submit']))
            {
              $plaats = $_POST['plaats'];
              $url .= "https://api.openweathermap.org/data/2.5/weather?q=".$plaats."&units=metric&appid=<api_key_goes_here>";
            } else
            {
              $url .= "https://api.openweathermap.org/data/2.5/weather?q=Rotterdam&units=metric&appid=<api_key_goes_here>";
              $urlForecast .= "https://api.openweathermap.org/data/2.5/onecall?lat=51.9225&lon=48&exclude=alerts&units=metric&appid=<api_key_goes_here>";
            }

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $data = curl_exec($ch);

            $newData = json_decode($data, true);

            $chForecast = curl_init();

            curl_setopt($chForecast, CURLOPT_URL, $urlForecast);
            curl_setopt($chForecast, CURLOPT_HEADER, 0);
            curl_setopt($chForecast, CURLOPT_RETURNTRANSFER, 1);

            $dataForecast = curl_exec($chForecast);

            $newDataForecast = json_decode($dataForecast, true);

            echo "<h3>".$newData["name"].", ".$newData["sys"]["country"]."</h3><br>";
            echo $newData["coord"]["lon"]." Lengte, ".$newData["coord"]["lat"]." Breedte<br>";
            echo $newData["main"]["temp"]." Graden Celsius<br>";

            //convert de integer tijd naar php tijd
            //hier gevonden https://www.epochconverter.com/programming/php
            $epochSunrise = $newData["sys"]["sunrise"];
            $epochSundown = $newData["sys"]["sunset"];

            $dtSunrise = new DateTime("@$epochSunrise");
            $dtSundown = new DateTime("@$epochSundown");

            echo $dtSunrise->format("H:i")." komt de zon op.\n";
            echo $dtSundown->format("H:i")." gaat de zon naar beneden.<br>";

            echo $newData["wind"]["speed"]." KM/U is de windsnelheid<br>";

            //laat een icoon zien
            ?>
            <img src="http://openweathermap.org/img/w/<?php echo $newData["weather"][0]["icon"]; ?>.png" class="weather-icon" /><br>
<!--		
			Moet ik nog aan werken
            <img src="http://openweathermap.org/img/w/<?php echo $newData["daily"][0]["weather"][0]["icon"]; ?>.png" class="weather-icon" /><br>
-->
            <?php
        	echo "<iframe width='500' height='300' src='https://api.maptiler.com/maps/hybrid/?key=<api_key_goes_here>#12.4/".$newData["coord"]["lat"]."/".$newData["coord"]["lon"]."'></iframe>"
        ?>
      </div>
    </div>
  </body>
</html>
