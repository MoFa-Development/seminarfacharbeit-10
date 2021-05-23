<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Seminarfacharbeit 10</title>

        <!--Libraries--->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="tabs is-large is-centered">
            <ul>
              <li><a href="index.html">Komprimieren</a></li>
              <li><a href="decompress.html">Dekomprimieren</a></li>
              <li><a href="stats.php">Statistiken</a></li>
              <li class="is-active"><a>About</a></li>
            </ul>
        </div>
        
        <div class="container is-max-desktop">
            <div class="notification is-primary">
              Diese Website nutzt das Git Repository unter <a href="https://github.com/Antricks/seminarfacharbeit-10">https://github.com/Antricks/seminarfacharbeit-10</a> der <strong>Version <?php exec("cd /home/ubuntu/Desktop/seminarfacharbeit-10/ && git rev-list --all --count 2>&1", $result); echo($result[0]); ?></strong>.
            </div>
          </div>
        <div class="card" style="margin: auto;  margin-top: 100px; padding: 50px; max-width: 1000px;">
          <h1 class="title">Grundlagenforschung zur Komprimierungsrate von Texten verschiedener Art</h1>
          <hr>
          <p>Untersucht wird die Länge eines komprimierten Textes im Vergleich zum Ursprungstext. Dabei basiert die Komprimierung auf dem mehrfachen Auftreten von Zeichenketten. Dabei wird die Frage gestellt, in wie fern aus der Komprimierungsrate auf den Inhalt der Texte geschlossen werden kann.</p>
          <br><br>
          <iframe id="doc" src="https://docs.google.com/document/d/e/2PACX-1vSh5ZAV3nPGvgzH5MkylqENeXsuXmYLUSlSQybOlUcY2VB0N8NGVbcGiWfis4Go1LzbD3sSe_twiH58/pub?embedded=true"></iframe>
        </div>
    </body>
</html>

<script>

</script>
