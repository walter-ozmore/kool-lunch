<!DOCTYPE html>
<html>
  <head>
    <title>Data | KoolLunches</title>
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>
    <style>
      table {
        width: 100%;
        border-collapse: collapse;
      }
      td {
        border: 1px solid;
      }

      .stats h2 {
        margin-top: 1em;
      }

      .content h2, h3 {
        margin-bottom: 0em;
      }

      .content p {
        margin: 0em;
        padding: 0em;
      }

      .big {
        font-size: 1.5em;
        padding: .01em 1em;
      }

      .sidebar {
        position: fixed;
        top: 10px;
        left: 10px;

        margin: 0px;
        padding: .5em;
      }

      .sidebar select {
        font-size: max(1em, 25%);
      }
    </style>

    <script>
      /**
       * Adds the given function to the selector dropdown. This is used to 
       * easly add sub pages to this page.
       */
      function addPage(name, func) {
        let index = pageFunctions.length;
        pageFunctions.push(func);
        pageSelector.append(
          $("<option>", {value: index}).text(name)
        );
      }

      /**
       * Fetch admin data from the server
       */
      async function fetchData(args = {}) {
        let response;
        try {
          response = await $.ajax({
            url: "/ajax/admin.php",
            method: "POST",
            data: args
          });
        } catch (error) {
          console.error("An error occurred during the AJAX request:", error);
          console.log(args);
          // displayError(args);
          return null;
        }

        // Parse and return
        try {
          // console.log(response);
          let data = JSON.parse(response);
          return data;
        } catch(error) {
          if(response.length > 0) {
            console.log(response);
            displayError(response);
          }
          return null;
        }
      }

      let pageSelector = $("<select>");
      let pageFunctions = [];
      $(document).ready(async function() {
        $("#float-thing").append(pageSelector);

        // Run the function of the selected page
        pageFunctions[ pageSelector.val() ]();
        pageSelector.change(()=>{
          pageFunctions[ pageSelector.val() ]();
        });
      });
    </script>

    <!-- Graphs -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@2.0.1/build/global/luxon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1.1.0/dist/chartjs-adapter-luxon.min.js"></script> -->

    <!-- Main page file -->
    <script src="/scripts/admin/overview.js"></script>

    <!-- Include script for pages -->
    <!-- <script src="/scripts/vd-day.js"></script> -->
    <!-- <script src="/scripts/vd-overview.js"></script> -->
    <!-- <script src="/scripts/vd-forms.js"></script> -->
  </head>

  <header>
    <?php include realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
		<!-- Dropdown for pages -->
    <div class="sidebar content" id="float-thing">
      <!-- <select id="selector"></select> -->
    </div>

    <div id="page"></div>
  </body>
</html>