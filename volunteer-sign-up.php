<!DOCTYPE html>
<html>
  <head>
    <title>Volunteer | Kool Lunches</title>

    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php"; ?>

    <script>
      function check() {
        var answer;

        switch($("input[name='volunteer-type']:checked").val()) {
          case "false":
            $("#wait-2").show();
            $("#wait-1").hide();
            $("#not-main").hide();
            break;
          case "true":
            $("#wait-2").show();
            $("#wait-1").show();
            break;
          default: $("#wait-1").hide();
        }

        switch($("input[name='is-main-contact']:checked").val()) {
          case "yes":
            $("#wait-2").show();
            $("#not-main").hide();
            $("*[name='contact-person']").hide();
            break;
          case "no" :
            $("#wait-2").show();
            $("#not-main").show();
            $("*[name='contact-person']").show();
            break;
        }


        // Show the type of contact they selected
        // Hide everything
        $("#contact-phone-section").hide();
        $("#contact-email-section").hide();
        $("#contact-messenger-section").hide();

        // Show selected
        switch($("input[name='prefer-comms']:checked").val()) {
          case "call": case "text":
            $("#contact-phone-section").show();
          break;
          case "email":
            $("#contact-email-section").show();
          break;
          case "fbm":
            $("#contact-messenger-section").show();
          break;
        }
      }

      async function submit() {
        // Grab our contact info
        let contactInfo = {};
        switch($("input[name='prefer-comms']:checked").val()) {
          case "call": case "text":
            contactInfo["phoneNumber"] = $("#phone-number").val();
          break;
          case "email":
            contactInfo["email"] = $("#email").val();
          break;
          case "fbm":
            contactInfo["fbm"] = $("#fbm").val();
          break;
        }

        // Check for selected opportunities
        let opp = [];
        if($("#vol-option-1").is(":checked")) opp.push("weekInTheSummer");
        if($("#vol-option-2").is(":checked")) opp.push("bagDecoration");
        if($("#vol-option-3").is(":checked")) opp.push("fundraising");
        if($("#vol-option-4").is(":checked")) opp.push("supplyGathering");

        // Turn all data into obj for individual
        let data = {
          firstName: $("#first-name").val(),
          lastName: $("#last-name").val(),
          contact: contactInfo,
          opportunities: opp,
          preferredContact: $("input[name='prefer-comms']:checked").val()
        };

        // Check if part of org, adds information if so
        let isOrg = $("input[name='volunteer-type']:checked").val()
        // isOrg is a string, therefore always try by itself
        if (isOrg == "true") {
          let org = {
            isMainContact: $("input[name='is-main-contact']").val(),
            orgName: $("#org-name").val()
          };

          data["org"] = org;
        }

        console.log(data);

        let msg = await post("/ajax/vol-su.php", data);

        // Submit message
        if(msg === 0) {
          displayAlert({
            title: "Thank You",
            text: "We look forward to working with you and will be in touch soon.",
            onClose: ()=>{
              // Redirect to home page
              window.location.href = '/';
            }
          });
        }
      }

      function sh(number, show=True) {
        if(show)
          for(let x=0;x<=number;x++) $('#wait-'+x).show();
        else
          for(let x=number; x<10; x++) $('#wait-'+x).hide();
      }

      $(document).ready(function() {
        // Unselect all checkboxes & radio buttons
        // $('input[type="checkbox"]').prop('checked', false);
        // $('input[type="radio"]').prop('checked', false);

        $("*[name='contact-person']").hide();

        check();
        $("input[name='volunteer-type']").change(check);
        $("input[name='is-main-contact']").change(check);
        $("input[name='prefer-comms']").change(check);

        $('input[name="age-gate"]').change(function() {
          sh(0, $(this).is(':checked'));
        });
      });
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div class="content form">
      <div class="description">
        <h2>Want to get involved?</h2>
        <p style="color: rgb(50,50,50)">The KOOL Lunches Program is growing and we are finding more ways for you to get involved! If you wish to get involved, please fill out this form and we will get in touch.</p>
      </div>

      <div class="section">
        <input type="checkbox" name="age-gate">
        <label style="display: inline;">I am 18 or older</label>
      </div>


      <div class="section" id="wait-0" style="display: none">
        <label>Are you signing up yourself or your organization?</label>
        <div class="radio">
          <input type="radio" value="false" name="volunteer-type"><label>I am an individual</label><br>
          <input type="radio" value="true" name="volunteer-type"><label>I am a part of a group or organization</label>
        </div>
      </div>

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

      <div id="wait-2" style="display: none">
        <div class="section" id="name-section">
          <label name="question">Name of person filling out the form</label>
          <div class="flex">
            <input type="text" placeholder="First Name" id="first-name" value="">
            <input type="text" placeholder="Last Name"  id="last-name"  value="">
          </div>
        </div>



        <div class="section" id="preferred-communication-section">
          <label name="question">Preferred Communication Method</label>
          <div class="radio">
            <input type="radio" name="prefer-comms" value="call"><label>Phone Call</label><br>
            <input type="radio" name="prefer-comms" value="text"><label>Text Message</label><br>
            <input type="radio" name="prefer-comms" value="email"><label>Email</label><br>
            <input type="radio" name="prefer-comms" value="fbm"><label>Facebook Messenger</label><br>
          </div>
        </div>


        <div class="section" id="contact-phone-section">
          <label name="question">Phone Number<span name="contact-person"> of main contact person</span></label>
          <input type="text" placeholder="(000) 000-0000" id="phone-number">
        </div>


        <!-- Contact email -->
        <div class="section" id="contact-email-section">
          <label name="question">Email<span name="contact-person">  of main contact person</span></label>
          <input type="text" placeholder="example@example.com" id="email">
        </div>

        <div class="section" id="contact-messenger-section">
          <label name="question">Facebook Messenger<span name="contact-person">  of main contact person</span></label>
          <input type="text" placeholder="" id="fbm">
        </div>


        <div class="section">
          <label>Volunteer Opportunities</label>
          <div class="radio">
            <input type="checkbox" id="vol-option-1"><label>Week in the summer</label><br>
            <input type="checkbox" id="vol-option-2"><label>Bag Decoration</label><br>
            <input type="checkbox" id="vol-option-3"><label>Fundraising</label><br>
            <input type="checkbox" id="vol-option-4"><label>Supply Gathering</label><br>
          </div>
        </div>


        <center><button class="large-button" onclick="submit()">Submit</button></center>
      </div>
    </div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>
