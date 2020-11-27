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
    $rid = $r["id"];

    echo"
    var trace$rid = {
        x: [1, 2, 3, 4],
        y: [10, 11, 12, 13],
        text: ['A<br>size: 40', 'B<br>size: 60', 'C<br>size: 80', 'D<br>size: 100'],
        mode: 'markers',
        marker: {
          size: [400, 600, 800, 1000],
          sizeref: 2,
          sizemode: 'area'
        }
      };
    ";

    $str += "trace".$rid.", ";
}
$str = rtrim($str, ", ");

echo "var data = [".$str."];";

?>



var layout = {
  title: 'Statistiken der Komprimierbarkeit von Texten verschiedenen Genres',
  showlegend: true,
};

var config = {responsive: true}

Plotly.newPlot('plot', data, layout, config);

</script>