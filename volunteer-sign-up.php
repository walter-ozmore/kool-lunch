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
            document.getElementById("first-name")
                .addEventListener("input", (event) => alert("Changed!"));

            document.getElementById("last-name")
                .addEventListener("input", (event) => alert("Changed!"));

        
            function ageGate() {
                $("#volunteerForm").show();
                $("#wait-0").show();
                $("#ageGate").hide();
            }

            function showSubmit() {
                $("#submit").show();
            }

            function check() {
                switch($("input[name='volunteer-type']:checked").val()) {
                    case "false":
                        $("#wait-1").hide();
                        $("#wait-2").show();
                        break;
                    case "true":
                        $("#wait-1").show();
                        $("#wait-2").show();
                        break;
                    default:
                        $("#wait-1").hide();
                        $("#wait-2").hide();
                        break;
                }

                

            }
        </script>
    </head>
    <header>
        <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
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
                    <!-- Individual or Org -->
                    <div class="section" id="wait-0" style="display: none">
                        <label>Are you signing up yourself or your group/organization?</label>
                        <div class="radio" onclick="check()">
                            <input type="radio" value="false" name="volunteer-type"><label>I am an individual</label><br>
                            <input type="radio" value="true" name="volunteer-type"><label>I am a part of a group or organization</label>
                        </div>
                    </div>
                    <!-- Org Information -->
                    <div class="section" id="wait-1" style="display: none">
                        <label>Are you the main point of contact in the organization?</label>
                        <div class="radio">
                            <input type="radio" value="true" name="is-main-contact"><label>Yes, I am the main contact</label><br>
                            <input type="radio" value="false" name="is-main-contact"><label>No, I am not the main contact</label>
                        </div>
                        <br>
                        <label name="question">What is the name of your organization?</label>
                        <input type="text" placeholder="Organization Name" id="org-name" value="">
                    </div>

                    <!-- Filler-outer's Name -->
                    <div class="section" id="wait-2" style="display: none">
                        <label name="question">Name of person filling out the form</label>
                        <div class="flex">
                            <input type="text" placeholder="First Name" id="first-name" value="" onchange="check()">
                            <input type="text" placeholder="Last Name"  id="last-name"  value="" onchange="check()">
                        </div>
                    </div>

                    <!-- Communication  -->
                    <div class="section" id="wait-3 preferred-communication-section" style="display: none">
                        <label name="question">Preferred Communication Method</label>
                        <div class="radio">
                            <input type="radio" name="prefer-comms" value="call"><label>Phone Call</label><br>
                            <input type="radio" name="prefer-comms" value="text"><label>Text Message</label><br>
                            <input type="radio" name="prefer-comms" value="email"><label>Email</label><br>
                            <input type="radio" name="prefer-comms" value="fbm"><label>Facebook Messenger</label><br>
                        </div>
                    </div>

                    <div class="section" id="contact-phone-section" style="display: none">
                        <label name="question">Phone Number<span name="contact-person"> of main contact person</span></label>
                        <input type="text" placeholder="(000) 000-0000" id="phone-number">
                    </div>

                    <div class="section" id="contact-email-section" style="display: none">
                        <label name="question">Email<span name="contact-person">  of main contact person</span></label>
                        <input type="text" placeholder="example@example.com" id="email">
                    </div>

                    <div class="section" id="contact-messenger-section" style="display: none">
                        <label name="question">Facebook Messenger<span name="contact-person">  of main contact person</span></label>
                        <input type="text" placeholder="" id="fbm">
                    </div>

                    <!-- Opportunities -->
                    <div class="section" style="display: none">
                        <label>Volunteer Opportunities</label>
                        <div class="radio" onclick="showSubmit()">
                            <input type="checkbox" id="vol-option-1"><label>Week in the summer</label><br>
                            <input type="checkbox" id="vol-option-2"><label>Bag Decoration</label><br>
                            <input type="checkbox" id="vol-option-3"><label>Fundraising</label><br>
                            <input type="checkbox" id="vol-option-4"><label>Supply Gathering</label><br>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <center><button onclick="" class="lexend-body stroke" id="submit" style="display: none">Submit</button>
                </div>
            </div>
        </div>
    </body>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>