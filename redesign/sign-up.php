<!DOCTYPE html>
<html>
    <head>
        <title>Signup | Kool Lunches</title>
        <link rel="stylesheet" href="/res/rf-gallery.css"/>
        <?php
            require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
            include realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/Parsedown.php";
        ?>

        <script>
        </script>
    </head>
    <header>
        <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/redesign/header.php"; ?>
    </header>
    <body>
        <div class="content">
            <div class="banner"><h1 class="lexend-deca-header stroke">SIGN UP</h1></div>
            <div id="disclaimer">
                <div class="section">
                    <center><p class="lexend-bold">Dates served are May 31st - August 4th, Monday through Thursday.</p></center>
                    <p class="lexend-body"> Sack lunches will be prepared for pickup at local parks and areas in Bonham. Lunches will be four days a week, Monday through Thursday, with the exception of July 4th in order to observe Independence Day.</p>
                    <p class="lexend-body">If you are interested in your child or children participating, please fill out the questionnaire and submit.</p>
                    <p class="lexend-body">If you have any questions, please contact The Kool Lunches Program at thekoollunchesprogram@gmail.com or message us on Facebook @ Kool Lunches.</p>
                    <p class="lexend-body">I understand that if I do not let The Kool Lunches Program know before 10:55am that I will not be picking up lunches that day, my name will be removed until I contact The Kool Lunches Program to begin recieving lunches again.</p>
                    <center><button onclick="showTopSection()" class="lexend-body stroke">I understand</button>
                </div>
            </div>
            <div id="topSection" class="form" style="display:none;">
                <div class="section">
                    <div id="pickerUpperDescDiv">
                        <center><h1 class="lexend-deca-header" style="margin-bottom: 0;">PICKER UPPERS</h1></center>
                        <p class="lexend-body informative"><img src="/res/images/info.png">   People listed here will be able to pickup the meals.</p>
                        <hr>
                    </div>
                    <div id="pickerUppersDiv"></div>
                    <button type="submit" class="lexend-body stroke" onclick="addPickerUpper();">Add Adult</button>
                </div>
            </div>
            <div id="middleSection" class="form" style="display:none;">
                <div class="section">
                    <center><h1 class="lexend-deca-header" style="margin-bottom: 0;">LUNCHES</h1></center>
                    <p class="lexend-body informative"><img src="/res/images/info.png">   Enter the number of lunches you will be needing, and any allergies your kids have.</p>
                    <hr>
                    <div id="lunchesNeededDiv">
                        <label class="lexend-body"># of Lunches Needed </label>
                        <input type="number" id="lunchesNeeded" min=1 value=1 required>
                    </div>
                    
                    <div id="photoConsentDiv">
                        <label class="lexend-body">Allow photos </label><input type="checkbox" id="photoConsent">
                    </div>

                    <div id="hasAllergiesDiv">
                        <label class="lexend-body">One of more of my kids have allergies </label><input type="checkbox" id="hasAllergies">
                    </div>
                    <div id="allergiesDiv">
                        <label class="lexend-body">Please list all allergies </label> <textarea id="allergies" rows="5" cols="80" placeholder="Blakely is allergic to books, Taylor can not have bananas."></textarea>
                    </div>
                </div>
            </div>
            <div id="bottomSection" class="form" style="display:none;">
                <div class="section">
                    <center><h1 class="lexend-deca-header" style="margin-bottom: 0;">PICKUP</h1></center>
                    <p class="lexend-body informative"><img src="/res/images/info.png">   Select the days you want to pick up, and the location you want to pick up from.</p>
                    <hr>
                    <div id="pickupDiv">
                        <label class="lexend-body">Pickup Days</label><br>
                        <input type="checkbox" id="pickupMon"> <label class="lexend-body">Mon</label> <br>
                        <input type="checkbox" id="pickupTue"> <label class="lexend-body">Tue</label> <br>
                        <input type="checkbox" id="pickupWed"> <label class="lexend-body">Wed</label> <br>
                        <input type="checkbox" id="pickupThu"> <label class="lexend-body">Thu</label> <br>
                        <label class="lexend-body">Pickup Location</label><div id="location-radio"></div>
                    </div>
                    <center><button type="submit" class="lexend-body stroke" onclick="submit();" id="submit-button">Sign Up</button></center>
                </div>
            </div>
            <div class="content" id="submission" style="display:none;">
                <div class="popup">
                    <p style="lexend-body">Thank you for your submission!</p>
                </div>
            </div>
        </div>
    </body>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/redesign/footer.php"; ?>
</html>