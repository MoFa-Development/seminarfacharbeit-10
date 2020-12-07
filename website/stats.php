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
              <a onclick="document.getElementById('plotFrame').src = 'https://seminarfach.zapto.org/statsFrame?ord=title'" class="dropdown-item <?php if($_GET["ord"] == "title") echo "is-active";?>">
                Einzelne Werke
              </a>
              <a onclick="document.getElementById('plotFrame').src = 'https://seminarfach.zapto.org/statsFrame?ord=author'" class="dropdown-item <?php if($_GET["ord"] == "author") echo "is-active";?>">
                Autor
              </a>
              <a onclick="document.getElementById('plotFrame').src = 'https://seminarfach.zapto.org/statsFrame?ord=genre'" class="dropdown-item <?php if($_GET["ord"] == "genre") echo "is-active";?>">
                Genre
              </a>
            </div>
          </div>
        </div>

        <input id="sliderWithValue" class="slider has-output is-fullwidth" min="1" max="100" value="0" step="1" type="range">
        <output for="sliderWithValue">1</output>

        <script>

          var dropdown = document.querySelector('.dropdown');
            dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
            dropdown.classList.toggle('is-active');
          }); 
        </script>



            <iframe id="plotFrame" src="http://seminarfach.zapto.org/statsFrame.php" style="height: 100% width: 100%">

            </iframe>
        
    </body>
</html>