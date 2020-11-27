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
              Diese Website nutzt den Komprimierungsalgorithmus der <strong>Version <?php exec("git rev-list --all --count 2>&1", $result); echo($result[0]); ?></strong>.
            </div>
          </div>
        
    </body>
</html>

<script>

</script>