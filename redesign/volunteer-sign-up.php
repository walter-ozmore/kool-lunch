<!DOCTYPE html>
<html>
    <head>
        <title>Volunteer | Kool Lunches</title>
        <link rel="stylesheet" href="/res/rf-gallery.css"/>
        <?php
            require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
            include realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
        ?>

        <script>
        </script>
    </head>
    <header>
        <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/redesign/header.php"; ?>
    </header>
    <body>
        <div class="content">
            <div class="banner"><h1 class="lexend-deca-header stroke">WANT TO GET INVOLVED?</h1></div>
            <div id="disclaimer">
                <div class="section" id="ageGate">
                    <p class="lexend-body">The KOOL Lunches Program is growing and we are finding more ways for you to get involved! Volunteers can help with bag decoration, fundraising, supply gathering, lunch making, and distribution. If you wish to get involved, please fill out this form and we will get in touch.</p>
                    <center><button onclick="ageGate()" class="lexend-body stroke">I am 18 or older</button>
                </div>
                <div class="form" id="volunteerForm" style="display: none;">
                    <div class="section" id="wait-0" style="display: none">
                        <label>Are you signing up yourself or your organization?</label>
                        <div class="radio">
                            <input type="radio" value="false" name="volunteer-type"><label>I am an individual</label><br>
                            <input type="radio" value="true" name="volunteer-type"><label>I am a part of a group or organization</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/redesign/footer.php"; ?>
</html>