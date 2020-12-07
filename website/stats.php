<?php
          if(!isset($_GET["amount"]))
          {
              $_GET["amount"] = 0;
          }
          if(isset($_GET["ord"]))
          {
              $ord = $_GET["ord"];
          }
          else
          {
              $ord = "Bitte auswählen";
          }
?>
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
              <span id="ddText"><?php if(isset($ord)) echo $ord; else echo "Bitte auswählen";?></span>
              <span class="icon is-small">
                <i class="fas fa-angle-down" aria-hidden="true"></i>
              </span>
            </button>
          </div>
          <div class="dropdown-menu" id="dropdown-menu" role="menu">
            <div class="dropdown-content">
              <a onclick="document.getElementById('ord').value = 'title'; document.getElementById('ddText').innerHTML = 'title'" class="dropdown-item <?php if($ord == "title") echo "is-active";?>">
                title
              </a>
              <a onclick="document.getElementById('ord').value = 'author'; document.getElementById('ddText').innerHTML = 'author'" class="dropdown-item <?php if($ord == "author") echo "is-active";?>">
                author
              </a>
              <a onclick="document.getElementById('ord').value = 'genre'; document.getElementById('ddText').innerHTML = 'genre'" class="dropdown-item <?php if($ord == "genre") echo "is-active";?>">
                genre
              </a>
            </div>
          </div>
        </div>

        <form action="stats.php" method="get">
          <input id="sliderWithValue" class="slider has-output is-fullwidth" min="0" max="100" value="<?php echo intval($_GET["amount"]);?>" step="1" type="range" name="amount">
          <output for="sliderWithValue"><?php echo intval($_GET["amount"]);?></output>
          <input type="submit" class="button" value="Diagramm erstellen"></input>
          <input type="hidden" id="ord" name="ord" value="<?php if(isset($ord)) echo $ord;?>">
        </form>

        <script>

          var dropdown = document.querySelector('.dropdown');
            dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
            dropdown.classList.toggle('is-active');
          });

          function findOutputForSlider(el) {
            var idVal = el.id;
            outputs = document.getElementsByTagName('output');
            for( var i = 0; i < outputs.length; i++ ) {
              if (outputs[i].htmlFor == idVal)
                return outputs[i];
            }
          }

          var sliders = document.querySelectorAll( 'input[type="range"].slider' );
          [].forEach.call( sliders, function ( slider ) {
            var output = findOutputForSlider( slider );
            if ( output ) {
              slider.addEventListener( 'input', function( event ) {
                output.value = event.target.value;
              } );
            }
          } );

        </script>

        <div id="plot"></div>

        <?php

          include "php/statsLoader.php";
          if($ord != "Bitte auswählen")
          {
            $fr = loadStats($ord);
          }

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
          $max = $_GET["amount"] + 1;
          foreach($fr as $r)
          {
            $rid = strval($r["id"]);
            $author = str_replace("'", "", $r["author"]);
            $author = str_replace("\r", "",$author);
            $author = str_replace("\n", "",$author);
            $charRate = $r["charRate"];
            $duplicateWords = $r["duplicateWords"];
            $topTenWords = $r["topTenWords"];
            $inputLen = $r["inputLen"];
            $outputLen = $r["outputLen"];
            $title = str_replace("'", "", $r["title"]);
            $genre = str_replace("\r", "",$r["genre"]);
            $genre = str_replace("\n", "",$genre);


            //Jump to next ord if max words reached for this ord reached
            if($counter >= $max)
            {
              $orderTypes[] = "skip";
              $counter = 0;
            }
            $counter += 1;

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
                      visible: 'legendonly',
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
            else
            {
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
          }

          $str = rtrim($str, ", ");


          echo "var data = [".$str."];";

        ?>



        var layout = {
          title: 'Statistiken der Komprimierbarkeit von Texten verschiedener Autoren',
          showlegend: true,
          hovermode: 'closest',
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