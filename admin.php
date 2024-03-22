<!DOCTYPE html>
<html>
  <head>
    <title>Admin | KoolLunches</title>
    <meta name="robots" content="noindex">
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>
    <style>
      .admin-container {
        display: flex;
        margin: 0em auto;
      }

      .sidebar {
        margin: .5em;
        padding: 10px 0em;
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

      #results {
        list-style-type: none;
        padding: 0;
      }

      #results li {
        cursor: pointer;
        padding: 5px;
        border: 1px solid #ccc;
        margin: 2px;
      }

      #loadingMessage {
        display: none;
      }
    </style>

    <script>
      let pages = {};

      /**
       * Adds a page to the document by adding the sidebar content
       */
      function addPage(name, func) {
        let sidebarEle = $("<p>", {class: "clickable"}).text(name);
        sidebarEle.click(()=>{selectPage(name)});

        $("#sidebar").append(sidebarEle);
        pages[name] = {
          sidebarEle: sidebarEle,
          func: func
        };
      }

      function selectPage(index) {
        // Remove highlights from all other sidebar elements & highlight
        $("#sidebar").find(".highlight").removeClass("highlight");
        pages[index].sidebarEle.addClass("highlight");

        // Clear view pane and make a new page to prevent content from one page
        // from loading on the other
        let page = $("<div>");
        let pane = $("#view-pane")
          .empty()
          .show()
          .append(page)
          .addClass("content")
        ;
        pages[index].func(page);
        selectedPageIndex = index;
      }

      /**
       * Refreshes the page if the selected page matches the given index, if the
       * index is undefined then it will always refresh
       */
      function refreshPage(expectedIndex=undefined) {
        if(expectedIndex != undefined && expectedIndex != selectedPageIndex)
          return
        selectPage(selectedPageIndex);
      }

      let autoSelected = false;
      let tableHeaderNames = {
        individualID: "ID",
        formID: "ID",
        orgID: "ID",
        individualName: "Name",
        orgName: "Name",
        timeSubmitted: "Submit Time",
        weekInTheSummer: "For a Week",
        bagDecoration: "Bag Decoration",
        fundraising: "Fundraising",
        supplyGathering: "Supplies",
        phoneNumber: "Phone Number",
        email: "Email",
        facebookMessenger: "FBM",
        preferredContact: "PMOC",
        isEnabled: "Enabled",
        lunchesNeeded: "Lunches Needed",
        allergies: "Allergies",
        pickupDays: "pickupDays",
        location: "Pickup Location",
			};
      let tableTriggers = [
				{ case: ["weekInTheSummer", "bagDecoration", "fundraising", "supplyGathering", "isEnabled"],
					func: function(data) { return (data == "1")? "Yes": "No"; }
				},
				{ case: ["timeSubmitted"],
					func: function(data) { return unixToHuman(data); }
				}
			];

      let selectedPageIndex = undefined;
      let page = undefined;
      $(document).ready(async function() {
        // Check if we have a user logged in
        let user = await account_getUser();
        if(user == null) {
          Account.createWindow();
          return;
        }

        let val;
        const urlParams = new URLSearchParams(window.location.search);

        // Check if
        val = urlParams.get('page');
        if (val !== null) selectPage( urlParams.get('page') );

        // Check if inspect individual is selected
        val = urlParams.get('InspectIndividual');
        if (val !== null) await inspectIndividual(val);
      });
    </script>

    <!-- Import Pages -->
    <!-- <script src="/scripts/admin/overview.js"></script> -->
    <script src="/scripts/admin/forms.js"></script>
    <script src="/scripts/admin/volunteer-forms.js"></script>
    <script src="/scripts/admin/individuals.js"></script>
    <script src="/scripts/admin/organizations.js"></script>
  </head>

  <header>
    <?php include realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
		<!-- Dropdown for pages -->
    <div class="admin-container">
      <div class="sidebar content" id="sidebar">
        <button onclick="refreshPage()">Refresh</button>
      </div>
      <div class="view-pane" id="view-pane"></div>
    </div>

    <?php include realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
  </body>
</html>