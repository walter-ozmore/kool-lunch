<!DOCTYPE html>
<html>
  <head>
    <title>Admin | KoolLunches</title>
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>
    <style>
      .admin-container {
        display: flex;
        max-width: 64em;
        margin: 0em auto;
      }

      .sidebar {
        margin: .5em;
        padding: 0em;
        border-right: groove 1px white;
        min-width: fit-content;
        height: fit-content;
      }

      .sidebar p {
        padding: .25em .5em;
        margin: 0em;
      }

      .view-pane {
        padding: .5em;
        margin: .5em;
        width: 100%;
        max-width: unset;
        overflow: auto;
        /* padding: .5em 0em 5em 0em; */
      }

      .highlight {
        background-color: gray;
        color: white;
      }
    </style>

    <script>
      /**
       * Adds a page to the document by adding the sidebar content
       */
      function addPage(name, func) {
        let select = function() {
          // Remove highlights from all other sidebar elements & highlight
          $("#sidebar").find(".highlight").removeClass("highlight");
          sidebarEle.addClass("highlight");

          // Clear and pass the page
          let page = $("#view-pane").empty().show();
          func(page);
        };
        let sidebarEle = $("<p>", {class: "clickable"}).text(name);
        sidebarEle.click(select);

        $("#sidebar").append(sidebarEle);
        if(autoSelected) return;

        select();
        autoSelected = true;
      }

      let autoSelected = false;
      let tableHeaderNames = {
				timeSubmitted: "Submit Time",
				weekInTheSummer: "For a Week",
				bagDecoration: "Bag Decoration",
				fundraising: "Fundraising",
				supplyGathering: "Supplies",
        phoneNumber: "Phone Number",
        individualName: "Name",
        individualID: "",
        email: "Email",
        facebookMessenger: "FBM",
        preferredContact: "PMOC",
        orgID: "",
        orgName: "Name",
			};
      let tableTriggers = [
				{ case: ["weekInTheSummer", "bagDecoration", "fundraising", "supplyGathering"],
					func: function(data) { return (data == "1")? "Yes": "No"; }
				},
				{ case: ["timeSubmitted"],
					func: function(data) { return unixToHuman(data); }
				}
			];
      $(document).ready(async function() {
        // lightMode();

        // Check if we have a user logged in
        let user = await account_getUser();
        if(user == null) {
          Account.createWindow();
          return;
        }
      });
    </script>

    <!-- Import Pages -->
    <!-- <script src="/scripts/admin/overview.js"></script> -->
    <script src="/scripts/admin/volunteer-forms.js"></script>
    <script src="/scripts/admin/individuals.js"></script>
    <script src="/scripts/admin/forms.js"></script>
    <script src="/scripts/admin/organizations.js"></script>
  </head>

  <header>
    <?php include realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
		<!-- Dropdown for pages -->
    <div class="admin-container">
      <div class="sidebar content" id="sidebar"></div>
      <div class="view-pane content" id="view-pane"></div>
    </div>

    <?php include realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
  </body>
</html>