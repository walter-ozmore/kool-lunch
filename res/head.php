<!-- Set Title Logo -->
<link rel="shortcut icon" href="/res/images/icon.png" type="image/png">

<!-- Import Fonts -->
<link href='https://fonts.googleapis.com/css?family=Noto Serif JP' rel='stylesheet'>
<link href='https://fonts.googleapis.com/css?family=Bebas Neue' rel='stylesheet'>
<link href='https://fonts.googleapis.com/css?family=Shadows Into Light Two' rel='stylesheet'>

<!-- Import Style Sheets -->
<link rel ="stylesheet" type ="text/css" href ="/res/global.css">
<link rel ="stylesheet" type ="text/css" href ="/res/notification.css">

<!-- Import Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1"></script>
<script src="/scripts/lib.js"></script>
<script src="/scripts/data.js"></script>
<script src="/account/version-3/lib.js"></script>

<!-- Luxon, for time in graphs -->
<script src="https://cdn.jsdelivr.net/npm/luxon@2.0.1/build/global/luxon.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1.1.0/dist/chartjs-adapter-luxon.min.js"></script>


<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

<script>
  <?php
    echo "code = ". ((isset($_GET["code"]))? "true": "false") .";";
  ?>
</script>