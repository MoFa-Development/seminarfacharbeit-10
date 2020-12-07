<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Seminarfacharbeit 10</title>

        <!--Libraries--->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>
        <script src='https://cdn.plot.ly/plotly-latest.min.js'></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="tabs is-large is-centered">
            <ul>
              <li><a href="index.html">Komprimieren</a></li>
              <li><a href="decompress.html">Dekomprimieren</a></li>
              <li class="is-active"><a>Statistiken</a></li>
              <li><a href="about.php">About</a></li>
            </ul>
        </div>
            
        <div class="dropdown" style="margin: auto">
          <div class="dropdown-trigger">
            <button class="button" aria-haspopup="true" aria-controls="dropdown-menu">
              <span>Bitte auswählen</span>
              <span class="icon is-small">
                <i class="fas fa-angle-down" aria-hidden="true"></i>
              </span>
            </button>
          </div>
          <div class="dropdown-menu" id="dropdown-menu" role="menu">
            <div class="dropdown-content">
              <a onclick="document.getElementById('ord').value = 'title" class="dropdown-item <?php if($_GET["ord"] == "title") echo "is-active";?>">
                Einzelne Werke
              </a>
              <a onclick="document.getElementById('ord').value = 'author" class="dropdown-item <?php if($_GET["ord"] == "author") echo "is-active";?>">
                Autor
              </a>
              <a onclick="document.getElementById('ord').value = 'genre" class="dropdown-item <?php if($_GET["ord"] == "genre") echo "is-active";?>">
                Genre
              </a>
            </div>
          </div>
        </div>

        <form action="stats.php" method="get">
          <input id="sliderWithValue" class="slider has-output is-fullwidth" min="1" max="100" value="<?php echo intval($_GET["amount"]);?>" step="1" type="range" name="amount">
          <output for="sliderWithValue"><?php echo intval($_GET["amount"]);?></output>
          <input type="submit" class="button" value="Diagramm erstellen"></input>
          <input type="hidden" id="ord" name="ord">
        </form>

        <script>

          var dropdown = document.querySelector('.dropdown');
            dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
            dropdown.classList.toggle('is-active');
          });
        </script>

        <div id="plot"></div>

        <?php

          include "php/statsLoader.php";

          if(isset($_GET["ord"]))
          {
              $ord = $_GET["ord"];
          }
          else
          {
              return;
          }
          $fr = loadStats($ord);
          echo $ord;
        ?>


        <script>

        <?php

          $str = "";

          $orderTypes = []; //z.B. alle Autoren oder Genre

          $rid_l = [];
          $author_l = [];
          $charRate_l = [];
          $duplicateWords_l = [];
          $topTenWords_l = [];
          $inputLen_l = [];
          $outputLen_l = [];
          $title_l = [];
          $genre_l = [];


          $counter = 0;
          $max = $_GET["amount"];
          foreach($fr as $r)
          {
            $rid = strval($r["id"]);
            $author = str_replace("'", "", $r["author"]);
            $charRate = $r["charRate"];
            $duplicateWords = $r["duplicateWords"];
            $topTenWords = $r["topTenWords"];
            $inputLen = $r["inputLen"];
            $outputLen = $r["outputLen"];
            $title = str_replace("'", "", $r["title"]);
            $genre = str_replace("\r", "",$r["genre"]);
            $genre = str_replace("\n", "",$genre);


            //Jump to next ord if max words reached for this ord reached
            $counter += 1;
            if($counter >= $max)
            {
              $orderTypes[] = ${$ord};
              $counter = 0;
            }

            if(end($orderTypes) != ${$ord})
            {

                /*
                    PRINT FULL TRACE
                */
                $ordVal = ${$ord};


                $texts = [];
                for ($i = 0; $i <= sizeof($rid_l); $i++)
                {
                $texts[] = "'Titel: ".$title_l[$i]."<br>Autor: ".$author_l[$i]."<br>CharRate: ".$charRate_l[$i]."%<br>Duplikatswörter: ".$duplicateWords_l[$i]."<br>InputLen: ".$inputLen_l[$i]."<br>OutputLen: ".$outputLen_l[$i]."'";
                }

                $texts = implode(", ", $texts);

                $rid_l = implode(", ", $rid_l);
                $author_l = implode(", ", $author_l);
                $charRate_l = implode(", ", $charRate_l);
                $duplicateWords_l = implode(", ", $duplicateWords_l);
                $topTenWords_l = implode(", ", $topTenWords_l);
                $inputLen_l = implode(", ", $inputLen_l);
                $outputLen_l = implode(", ", $outputLen_l);
                $title_l = implode(", ", $title_l);
                $genre_l = implode(", ", $genre_l);

                echo"
                var t$rid = {
                    x: [$inputLen_l],
                    y: [$charRate_l],
                    name: '$ordVal',
                    hovertemplate: '%{text}',
                    text: [$texts],
                    mode: 'markers',
                    marker: {
                    size: [$duplicateWords_l],
                    sizeref: 2,
                    sizemode: 'area',
                    opacity: 0.3
                    }
                };
                ";
                $str .= "t".$rid.", ";

                /*
                    PRINT FULL TRACE END
                */

                $rid_l = [];
                $author_l = [];
                $charRate_l = [];
                $duplicateWords_l = [];
                $topTenWords_l = [];
                $inputLen_l = [];
                $outputLen_l = [];
                $title_l = [];
                $genre_l = [];

                $orderTypes[] = ${$ord};
            }
                $rid_l[] = $rid;
                $author_l[] = $author;
                $charRate_l[] = $charRate;
                $duplicateWords_l[] = $duplicateWords;
                $topTenWords_l[] = $topTenWords;
                $inputLen_l[] = $inputLen;
                $outputLen_l[] = $outputLen;
                $title_l[] = $title;
                $genre_l[] = $genre;
          }

          $str = rtrim($str, ", ");


          echo "var data = [".$str."];";

        ?>



        var layout = {
          title: 'Statistiken der Komprimierbarkeit von Texten verschiedener Autoren',
          showlegend: true,
          xaxis: {
          title: 'InputLen'
        },
          yaxis: {
          title: 'CharRate'
        }
        };

        var config = {responsive: true}

        Plotly.newPlot('plot', data, layout, config);

      </script>
    </body>
</html>