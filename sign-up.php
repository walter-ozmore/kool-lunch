<!DOCTYPE html>
<html>
  <head>
    <title>Sign Up | KoolLunches</title>

    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>

    <script>
      function showMiddleSec() {
        $("#topSec").hide();
        $("#middleSec").show();
        $("#bottomSec").hide();
      }

      function showBottomSec() {
        $("#topSec").hide();
        $("#middleSec").hide();
        $("#bottomSec").show();
      }

      function addPickerUpper() {
        let div = $("<div>", {class: "content"});

        let pickerUpperLinks = {
          nameInput: $("<input>", {type: "text"}),
          phoneInput: $("<input>", {type: "text"}),
          remindSelection:  $("<input>", {type: "checkbox"}),
          removeButton: $("<button>").text("Remove this individual").click(()=>{
            // Remove the div
            div.remove();

            // Remove the element from the array
            pickerUppers.filter(item => item !== pickerUpperLinks);
          }),
        };
        pickerUppers.push(pickerUpperLinks);

        div.append(
          $("<div>", {class: "section"}).append(
            $("<label>").text("Name of individual"), pickerUpperLinks.nameInput,
          ),
          $("<div>", {class: "section"}).append(
            $("<label>").text("Phone Number"), pickerUpperLinks.phoneInput,
          ),
          $("<div>", {class: "section"}).append(
            pickerUpperLinks.remindSelection, $("<label>", {style: "display: inline"}).text("I would like updates via remind"),
          ),
          $("<center>").append( pickerUpperLinks.removeButton ),
        );
        $("#pickerUppersDiv").append(div);
      }


      var pickerUppers = [];
      $(document).ready(function() {
        // Later on we will fetch this info from the database
        let locationRadioDiv = $("#location-radio");

        let locations = ["Pizza Hut", "Simpson Park", "Powder Creak Park", "T.E.A.M. Center Housing Authority"];
        for(let location of locations) {
          locationRadioDiv.append(
            $("<input>", {type: "radio", value: location, name: "location"}),
            $("<p>", {style: "display: inline"}).text(location),
            $("<br>")
          );
        }

        // Add a trigger for allergies checkbox
        let checkbox = $("#hasAllergies").change(function() {
          if ($(this).is(':checked')) {
            $('#allergy-div').show();
          } else {
            $('#allergy-div').hide();
          }
        });


        addPickerUpper();
      });
    </script>

    <style>
      .subdiv {
        margin-left: 2em;
      }

      .error {
        color: red;
        margin: 0px;
        padding: 0px;
      }
    </style>
  </head>

  <header>
    <?php
      include realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php";
    ?>
  </header>

  <body>
    <!-- Top section of the signup form -->
    <div class="topSec" id="topSec">
      <div class="content">
        <center><h1>Sign Up</h1></center>

        <center>
          <p><b><u>Dates served are May 31st - August 4th Monday-Thursdays</u></b></p>
        </center>
        <p>Sack lunches will be prepared for pick up at local parks and areas in Bonham. Lunches will be four days a week, Monday through Thursday, with the exception of July 4th in order to observe Independence Day.</p>
        <p>If you are interested in your child or children participating please fill out the questionnaire at the bottom and submit.</p>
        <p>If you have any questions, please contact The Kool Lunches Program at thekoollunchesprogram@gmail.com or message us on Facebook @ Kool Lunches.</p>
        <br>
        <p>I understand that I do not let the Kool Lunches Program know before 10:55 that I will not be picking up lunches that day, my name will removed until ontact the Kool Lunches Program to begin receiving lunches again.</p>
      </div>
      <center>
        <button onclick="showMiddleSec()" class="large-button">I understand</button>
      </center>
    </div>

    <div id="middleSec" class="form" style="display: none;">
      <center>
        <div style="max-width: 75%">
          <h2>Picker Uppers</h2>
          <p>People listed here will be able to pickup the meals</p>
        </div>
      </center>
      <div id="pickerUppersDiv"></div>
      <center>
        <button type="submit" class="large-button" onclick="addPickerUpper();">Add Adult</button>
        <button type="submit" class="large-button" onclick="showBottomSec();">Continue</button>
      </center>
    </div>

    <div id="bottomSec" class="form" style="margin-top: 1em; display: none;">
      <div class="content">
        <div class="section">
          <label># Of Lunches Needed</label>
          <input type="number" id="childNumber" min=1 value=1 onchange="renderChildren()">
        </div>

        <!-- Somehow t -->
        <div class="section">
          <input type="checkbox" id="hasAllergies"> <label style="display: inline">One or more of my kids have allergies</label> <br>
          <div id="allergy-div" style="margin-top: .5em; display: none">
            <label>Please list all allergies</label>
            <input type="text" placeholder="Blakely is allergic to books, Taylor can not have bananas." style="width: 100%">
          </div>
        </div>


        <div class="section">
          <label>Pickup Days</label>
          <input type="checkbox" id="pickupMon"> <p style="display: inline">Mon</p> <br>
          <input type="checkbox" id="pickupTue"> <p style="display: inline">Tue</p> <br>
          <input type="checkbox" id="pickupWed"> <p style="display: inline">Wed</p> <br>
          <input type="checkbox" id="pickupThu"> <p style="display: inline">Thu</p> <br>
        </div>

        <div class="section">
          <label>Pickup Location</label>
          <div id="location-radio"></div>
        </div>
      </div>

      <center>
        <button type="submit" class="large-button" onclick="showMiddleSec();">Back</button>
        <button type="submit" class="large-button" onclick="send();">Sign Up</button>
      </center>
    </div> <!-- Form -->

    <div class="content" id="submission" style="display: none;">
      Thank you for your submission.
    </div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>