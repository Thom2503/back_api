 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Welke films draaien er morgen?</title>
     <style media="screen">
       @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap');
       * {
         font-family: 'Josefin Sans', sans-serif;
       }
       .img {
         background-image: url(img/popcorn.png);
         background-size: 3.2em 3.2em;
         background-repeat: repeat;
         position: absolute;
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
       .info {
         position: relative;
         width: 75%;
         left: 15%;
       }
     </style>
   </head>
   <body>
     <h1>Welke films draaien er morgen?</h1>
     <div class="img">
       <div class="container">
         <div class="info">
           <?php

             $morgen = date("d-m-Y", strtotime("+1 day"));

             $url = "http://api.filmtotaal.nl/filmsoptv.xml?apikey=<api_key_goes_here>&dag=".$morgen."&sorteer=0";
             $ch = curl_init();

             curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_HEADER, 0);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

             $data = curl_exec($ch);

             $newData = new SimpleXMLElement($data);

             foreach($newData as $data)
             {
           	  $titel = (string) $data->titel;
              $tagline = (string) $data->tagline;
           	  $jaar = (int) $data->jaar;
           	  $duur = (int) $data->duur;
           	  $genre = (string) $data->genre;
           	  $genre = str_replace(":", ", ", $genre);
           	  $cast = $data->cast;

           	  printf("<h3>%s</h3><h4>%s</h4><em>%d </em>|<em> %s </em>|<em> %d minuten</em><br>", $titel, $tagline, $jaar, $genre, $duur);
           	  echo "Land: ".str_replace(":", ", ", $data->land)."<br>";
           	  echo "Regisseur is: ".$data->regisseur."<br>";
           	  echo "Cast is: ".str_replace(":", ", ", $cast)."<br>";

           	  echo "<img style='width: 20%; height: 20%;'src='".$data->cover."' alt='".$titel."'><br>";

           	  $epochBegin = $data->starttijd;
               $epochEind = $data->eindtijd;

               $dtBegin = new DateTime("@$epochBegin");
               $dtEind = new DateTime("@$epochEind");

           	  echo "Zendtijd: ".$dtBegin->format('H:i')." tot ".$dtEind->format('H:i')."";
           	  echo " op ".$data->zender."";

           	  $synopsis = (string) $data->synopsis;
           	  echo "<h5>Synopsis</h5>";
           	  printf("<p>%s</p>", $synopsis);

           	  echo "<h5>IMDB Data</h5>";
              $imdb_id = (string) $data->imdb_id;
           	  $imdb_rate = (int) $data->imdb_rating;
           	  $imdb_votes = (int) $data->imdb_votes;
           	  printf("ID: %s <br>Beoordeling: %d <br> Stemmen: %d<br>", $imdb_id, $imdb_rate, $imdb_votes);
           	  //https://www.imdb.com/title/tt0149460/ https://www.filmtotaal.nl/film/18078
           	  echo "Links: <a target='_blank' href='https://www.imdb.com/title/tt".$imdb_id."/'>IMDB</a> <a target='_blank' href='".$data->ft_link."'>Filmtotaal</a>";
              echo "<hr>";
             }
             var_dump("")
            ?>
         </div>
       </div>
     </div>
   </body>
 </html>
