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

?>


<script>
var trace1 = {
  x: [1, 2, 3, 4],
  y: [10, 11, 12, 13],
  text: ['A<br>size: 40', 'B<br>size: 60', 'C<br>size: 80', 'D<br>size: 100'],
  mode: 'markers',
  marker: {
    size: [400, 600, 800, 1000],
    sizemode: 'area'
  }
};

var trace2 = {
  x: [1, 2, 3, 4],
  y: [14, 15, 16, 17],
  text: ['A</br>size: 40</br>sixeref: 0.2', 'B</br>size: 60</br>sixeref: 0.2', 'C</br>size: 80</br>sixeref: 0.2', 'D</br>size: 100</br>sixeref: 0.2'],
  mode: 'markers',
  marker: {
    size: [400, 600, 800, 1000],
    //setting 'sizeref' to lower than 1 decreases the rendered size
    sizeref: 2,
    sizemode: 'area'
  }
};

var trace3 = {
  x: [1, 2, 3, 4],
  y: [20, 21, 22, 23],
  text: ['A</br>size: 40</br>sixeref: 2', 'B</br>size: 60</br>sixeref: 2', 'C</br>size: 80</br>sixeref: 2', 'D</br>size: 100</br>sixeref: 2'],
  mode: 'markers',
  marker: {
    size: [400, 600, 800, 1000],
    //setting 'sizeref' to less than 1, increases the rendered marker sizes
    sizeref: 0.2,
    sizemode: 'area'
  }
};

// sizeref using above forumla
var desired_maximum_marker_size = 40;
var size = [400, 600, 800, 1000];
var trace4 = {
  x: [1, 2, 3, 4],
  y: [26, 27, 28, 29],
  text: ['A</br>size: 40</br>sixeref: 1.25', 'B</br>size: 60</br>sixeref: 1.25', 'C</br>size: 80</br>sixeref: 1.25', 'D</br>size: 100</br>sixeref: 1.25'],
  mode: 'markers',
  marker: {
    size: size,
    //set 'sizeref' to an 'ideal' size given by the formula sizeref = 2. * max(array_of_size_values) / (desired_maximum_marker_size ** 2)
    sizeref: 2.0 * Math.max(...size) / (desired_maximum_marker_size**2),
    sizemode: 'area'
  }
};

var data = [trace1, trace2, trace3, trace4];





var layout = {
  title: 'Statistiken der Komprimierbarkeit von Texten verschiedenen Genres',
  showlegend: true,
};

var config = {responsive: true}

Plotly.newPlot('plot', data, layout, config);

</script>