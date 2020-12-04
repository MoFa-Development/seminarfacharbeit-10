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
        
            <div id="plot"></div>
        
    </body>
</html>

<?php

    include "php/statsLoader.php";
    $fr = $final_result;

?>


<script>

<?php

$str = "";
foreach($fr as $r)
{
    $rid = strval($r["id"]);

    $author = $r["author"];
    $charRate = $r["charRate"];
    $duplicateWords = $r["duplicateWords"];
    $topTenWords = $r["topTenWords"];
    $inputLen = $r["inputLen"];
    $outputLen = $r["outputLen"];

    echo"
    var trace$rid = {
        x: [$inputLen],
        y: [$outputLen],
        name: '$author',
        text: ['Author: $author<br>CharRate: $charRate%<br>Duplikatswörter: $duplicateWords<br>InputLen: $inputLen<br>OutputLen: $outputLen'],
        mode: 'markers',
        marker: {
          size: [$duplicateWords*100],
          sizeref: 2,
          sizemode: 'area'
        }
      };
    ";
    $str .= "trace".$rid."  , ";
}
$str = rtrim($str, ", ");

echo "var data = [".$str."];";

?>



var layout = {
  title: 'Statistiken der Komprimierbarkeit von Texten verschiedener Autoren',
  showlegend: true,
};

var config = {responsive: true}

Plotly.newPlot('plot', data, layout, config);

</script>