<!DOCTYPE html>
<html>
  <head>
    <title>Data | KoolLunches</title>
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>
    <style>
      .content h2, h3 {
        margin-bottom: 0em;
      }

      .content p {
        margin: 0em;
        padding: 0em;
      }

      .filters {
        text-align: center;
      }
      .filters select {
        font-size: 1.5em;
      }

      .sform label, input{
        font-size: 1.25em;
      }
    </style>

    <!-- <script src="/account/version-3/lib.js"></script> -->

    <script>
      /**
       * Gather and display all data from the forms for this year
       */
      function calculateGeneralStatistics() {}


      /**
       * Updates the shown data to match the filter
       */
      function updateFilter() {}


      /**
       * Create a notification object that contains the given form
       */
      function showForm(formId) {

      }

      ajaxJson("/ajax/fetch-data.php", function(obj) {
        data = obj;
        console.log(obj);

        onWindowLoad(function() {
          let ele = createFormElement(86);


          document.body.appendChild(ele);
        });
      });
    </script>
  </head>

  <header>
    <?php
      include realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php";
    ?>
  </header>

  <body>
    <div class="notification sform" style="display: none">
      <h2>Login</h2>
      <div class="grid" id="login">
        <label>Username</label>
        <input type="text" name="uname">

        <label>Password</label>
        <input type="password" name="pword">
      </div>
      <center><button onclick="account_login(draw)">Login</button></center>
    </div>

    <div id="stats" class="content">
      <h2>Statistics</h2>
    </div>

    <div class="content filters" style="display: none">
      <select id="location-selector" onchange="updateFilter()">
        <option value="T.E.A.M. Center Housing Authority">T.E.A.M. Center Housing Authority</option>
        <option value="Pizza Hut">Pizza Hut</option>
        <option value="Powder Creak Park">Powder Creak Park</option>
        <option value="Simpson Park">Simpson Park</option>
      </select>
    </div>

    <div class="content form">
      <p>Form ID: 76</p>
      <p>Pickup Days: M T W Th </p>
      <p>Time Submited: 1:03pm May 6, 2023</p>
      <p>Pickup Location: T.E.A.M. Center Housing Authority</p>
      <h3 style="text-align: center;">Adults</h3>
      <table>
        <tr>
          <th>Name</th>
          <th>Phone Number</th>
          <th>Remind Status</th>
        </tr>
        <tr>
          <td>Shanbricca Brown</td>
          <td>(903) 304-9284</td>
          <td>0</td>
        </tr>
      </table>

      <h3 style="text-align: center;">Children</h3>
      <table>
        <tr>
          <th>Name</th>
          <th>Allergies</th>
          <th>Allow Photos</th>
        </tr>
        <tr>
          <td>Serenity</td>
          <td></td>
          <td>true</td>
        </tr>
        <tr>
          <td>Salisitee</td>
          <td></td>
          <td>true</td>
        </tr>
      </table>
    </div>
  </body>
</html>