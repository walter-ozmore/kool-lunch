<!DOCTYPE html>
<html>
    <head>
        <title>Kool Lunches - About</title>
        <link rel="stylesheet" href="/res/rf-gallery.css"/>
        <?php
            require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
            include realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/Parsedown.php";
        ?>
    </head>
    <header>
        <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/redesign/header.php"; ?>
    </header>
    <body>
        <div class="content">
            <div class="banner"><h1 class="lexend-deca-header stroke">ABOUT US</h1></div>

            <div class="section two-columns">
                <div>
                    <div id="board-members">
                        <center><p class="lexend-bold" style="margin-bottom: 0px;">Board Members</p></center>
                        <hr>
                        <p>Need to make this pull from backend</p>
                    </div>
                    <div id="website-members">
                        <center><p class="lexend-bold" style="margin-bottom: 0px;">Website</p></center>
                        <hr>
                        <p>Need to make this pull from backend</p>
                    </div>
                    <div id="buildings">
                        <center><p class="lexend-bold" style="margin-bottom: 0px;">Building</p></center>
                        <hr>
                        <p>Need to make this pull from backend</p>
                    </div>
                </div>
                <div>
                    <div id="volunteer-members">
                        <center><p class="lexend-bold" style="margin-bottom: 0px;">Volunteers</p></center>
                        <hr>
                        <p>Need to make this pull from backend</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/redesign/footer.php"; ?>
</html>