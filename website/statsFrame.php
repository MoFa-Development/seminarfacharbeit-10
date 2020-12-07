<html>
    <head>
        <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    </head>

    <body>
        
        <div id="plot"></div>
    
    </body>
</html>

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