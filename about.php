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
        <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
    </header>
    <body>
        <div class="content">
            <div class="banner"><h1 class="lexend-deca-header stroke">ABOUT US</h1></div>

            <div class="section two-columns">
                <div>
                    <?php
                        $setting = Database::getSetting("mentionsText");
                        $markdown = $setting["value"];

                        // Split the string into an array of lines using the newline character (\n) as the delimiter
                        $lines = explode("\n", $markdown);

                        $isDivOpen = False;
                        foreach($lines as $line) {
                            if( strpos($line, "# ") !== 0 ) {
                                echo "<p>$line</p>";
                                continue;
                            }
                            
                            if($isDivOpen) {
                                echo "</div>";
                                $isDivOpen = False;
                            }
                            $isDivOpen = True;

                            $line = substr($line, 2);
                            echo "<div>";
                            echo "<center><p class='lexend-bold' style='margin-bottom: 0px;'>$line</p></center>";
                            echo "<hr>";
                        }
                        if($isDivOpen) {
                            echo "</div>";
                            $isDivOpen = False;
                        }
                    ?>
            </div>
        </div>
    </body>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>