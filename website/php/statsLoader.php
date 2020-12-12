<?php
        //Mit Server verbinden und Datenbank auswaehlen
        $database = mysqli_connect("localhost", getenv("SF_DB_NAME"), getenv("SF_DB_PASSWORD")) or die("Connection failed: " . mysqli_error($database));
        $db_selected = mysqli_select_db($database, "seminarfach");
        
        

        if (!$db_selected)
        {
            $sql = 'CREATE DATABASE seminarfach';
            $database->query($sql);
            $db_selected = mysqli_select_db($database, "seminarfach");
        }
        
        $database->set_charset("utf8");
        
function loadStats($ord, $av)
{
        global $database;


        if (!$av)
            $sql = "SELECT * FROM articles ORDER BY ".$ord;
        else
            $sql = "SELECT AVG(id), AVG(topWords), AVG(charRate), AVG(genre), AVG(url), AVG(author), AVG(title), AVG(inputLen), AVG(outputLen), AVG(duplicateWords), AVG(execTime) FROM articles GROUP BY ".$ord." ORDER BY ".$ord;


        $result = $database->query($sql);
        
        if(empty($result))
        {
            $sql = "CREATE TABLE articles (
                      id int(11) AUTO_INCREMENT,
                      topWords varchar(100) NOT NULL,
                      inputLen int(11) NOT NULL,
                      outputLen int(11) NOT NULL,
                      duplicateWords int(11) NOT NULL,
                      charRate int(3) NOT NULL,
                      author varchar(30) NOT NULL,
                      PRIMARY KEY  (ID)
                      )";
            $database->query($sql);
            
            $sql = "SELECT * FROM articles";
            $result = $database->query($sql);
        }

        $final_result = [];

        $rowNr = 0;
        while($row = mysqli_fetch_assoc($result)) 
        {
            foreach ($row as $key => $value) {
                unset($row[$key]);
                $row[str_replace(")", "", str_replace("AVG(", "", $key))] = $value;
            }

            $final_result[$rowNr] = $row;
            $rowNr += 1;
        }

        return $final_result;
}

function average($a)
{
    $a = array_filter($a);
    $average = array_sum($a)/count($a);
    return   $average;
}