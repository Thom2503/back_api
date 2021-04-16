<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">
    @import url('https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap');
    * {
      font-family: 'Kiwi Maru', serif;
    }
    body {
      background-image: url(img/anime.png);
      background-repeat: repeat;
      background-size: 6.7em 5.2em;
    }
    h1 {
      text-align: center;
    }
    .container {
      position: relative;
      width: 80%;
      border: 1px solid black;
      margin: 0 auto;
      background-color: #fdf6e3;
    }
    .info, .binnenform {
      position: relative;
      width: 75%;
      left: 15%;
    }
    .form {
      position: relative;
      width: 80%;
      border: 1px solid black;
      margin: 0 auto;
      background-color: #fdf6e3;
    }
     /* Popup container */
	.popup {
	  position: relative;
	  display: inline-block;
	  cursor: pointer;
	}

	/* The actual popup (appears on top) */
	.popup .popuptext {
	  visibility: hidden;
	  width: 20em;
	  background-color: #555;
	  color: #fff;
	  text-align: center;
	  border-radius: 6px;
	  padding: 8px 0;
	  position: absolute;
	  z-index: 1;
	  bottom: 125%;
	  left: 50%;
	  margin-left: -80px;
	}

	/* Popup arrow */
	.popup .popuptext::after {
	  content: "";
	  position: absolute;
	  top: 100%;
	  left: 25%;
	  margin-left: -5px;
	  border-width: 5px;
	  border-style: solid;
	  border-color: #555 transparent transparent transparent;
	}

	/* Toggle this class when clicking on the popup container (hide and show the popup) */
	.popup .show {
	  visibility: visible;
	  -webkit-animation: fadeIn 1s;
	  animation: fadeIn 1s
	}

	/* Add animation (fade in the popup) */
	@-webkit-keyframes fadeIn {
	  from {opacity: 0;}
	  to {opacity: 1;}
	}

	@keyframes fadeIn {
	  from {opacity: 0;}
	  to {opacity:1 ;}
	} 
    </style>
  </head>
  <body>
    <form class="form" action="" method="post">
      <div class="binnenform">
		<h1>Kitsu Api || Info about anime</h1>
        <h2>Search an anime</h2><br>
        <select id="mediums" name="mediums">
          <option value="anime">Anime</option>
          <option value="manga">Manga</option>
        </select>
        <input type="text" name="serie" placeholder="Pokemon indigo league">
        <input type="submit" name="submit" value="Zoeken">
      </div>
	  <div class="popup" onclick="popupFunctie()">Meer info!
		<span class="popuptext" id="myPopup">Paar voorbeelden om op te zoeken kunnen bijvoorbeeld zijn:
		Anime: pokemon, kimetsu no yaiba, naruto. Manga: komi can't communicate, girls' last tour.</span>
	  </div> 
    </form>
    <div class="container">
      <div class="info">
        <?php
          $url = "";
          if (isset($_POST['submit']))
          {
            $anime = $_POST['serie'];
            $medium = $_POST['mediums'];
            $anime = str_replace(" ", "%20", $anime);
            $url .= "https://kitsu.io/api/edge/".$medium."?page[limit]=5&filter[text]=".$anime;
          } else
          {
            //default is anime set it here
            $medium = "anime";
            $url .= "https://kitsu.io/api/edge/anime?page[limit]=5&filter[text]=kimetsu%20no%20yaiba";
          }
          $ch = curl_init();

          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

          $data = curl_exec($ch);

          $newData = json_decode($data, true);

          for ($i=0; $i < 3; $i++)
          {
            echo "<h3>".$newData['data']["$i"]['attributes']['titles']['en_jp']."<br>";
            echo $newData['data']["$i"]['attributes']['titles']['ja_jp']."</h3><br>";
            echo "<em>".$newData['data']["$i"]['attributes']['ageRating']." | ".$newData['data']["$i"]['attributes']['ageRatingGuide']." | ".$newData['data']["$i"]['attributes']['subtype']."</em><br>";
            echo "<img src='".$newData['data']["$i"]['attributes']['posterImage']['medium']."'><br>";
            echo $newData['data']["$i"]['attributes']['synopsis']."<br>";
            echo "<h4>Status</h4>";
            echo "Begin date: ".$newData['data']["$i"]['attributes']['startDate']."<br>";
            echo "End date: ".$newData['data']["$i"]['attributes']['endDate']."<br>";
            echo $newData['data']["$i"]['attributes']['status']."<br>";
            //to show either the volumes for manga or the episodes for anime
            if ($medium == "anime")
            {
              $header = "<h4>Episodes</h4>";
              $episodes = $newData['data']["$i"]['attributes']['episodeCount']." number of episodes<br>";
              $length =  $newData['data']["$i"]['attributes']['episodeLength']." minutes<br>";
            } else
            {
              $header = "<h4>Volumes</h4>";
              $episodes = $newData['data']["$i"]['attributes']['chapterCount']." number of chapters<br>";
              $length =  $newData['data']["$i"]['attributes']['volumeCount']." number of volumes<br>";
            }
            echo $header;
            echo $episodes;
            echo $length;
          }
         ?>
      </div>
    </div>
  </body>
  <script>
	function popupFunctie() {
	  var popup = document.getElementById("myPopup");
	  popup.classList.toggle("show");
	}
  </script>
</html>
