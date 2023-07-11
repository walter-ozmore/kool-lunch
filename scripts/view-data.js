/**
 * Using the global variable data it will attempt to calculate various stats
 * about the data
 *
 * @returns Object containing all stats about the loaded data
 */
function calculateStats() {
  // Create counter object for the stats
  let counter = {};

  // Create lists of information to track
  let locations = data["locations"];

  // Create counter obj

  for(let day of days) {
    counter[day] = { lunches: 0, allergies: [] };
    for(let location of locations) {
      counter[day][location] = { lunches: 0, allergies: [] };
    }
  }

  // Go though the data and fill up the counter object
  for(let index in data["forms"]) {
    let form = data["forms"][index];

    // Skip enabled
    if(form["isEnabled"] != 1) continue;

    let location = form["Location"];

    for(let ind of form["individuals"]) {
      // Individual is adult
      if(ind.IsAdult == 1) continue;

      if(ind.Allergies != null && ind.Allergies.length > 0) {
        let allergyObject = {
          allergies: ind["Allergies"],
          formIndex: index
        };

        for(let day of form["pickupDays"]) {
          counter[day]["allergies"].push( allergyObject );
          counter[day][location]["allergies"].push( allergyObject );
        }
      }
    }

    for(let day of form["pickupDays"]) {
      counter[day].lunches += Number(form["lunchesNeeded"]);
      counter[day][location].lunches += Number(form["lunchesNeeded"]);
    }
  }
  return counter;
}


/**
 * Adds a page to the HTML document, and the page list after data is loaded. If
 * data has not be loaded then the page's initalization will be saved till data
 * is loaded.
 *
 * @param {*} page
 */
function addPage(page) {
  if(dataLoaded == false) {
    // Save to load later
    pagesToAddLater.push(page);
    return;
  }

  // Add to the drop down
  let option = $("<option>")
    .val(page.id)
    .text(page.name)
  ;
  $("#selector").append(option);

  // Add page div
  let div = $("<div>")
    .attr("id", page.id)
    .hide()
  ;
  $("#pages").append(div);

  // Initalize page
  if("init" in page) page.init();

  // Add to page object
  page.div = div;
  pages[page.id] = page;
}


/**
 * Hides all pages using JQuery
 */
function hidePages() {
  for(let pageId in pages)
    $("#"+pageId).hide();
}


/**
 * Shows the given page using JQuery and their ID
 *
 * @param pageId
 */
function showPage(pageId) {
  $("#"+pageId).show();
}

var dataLoaded = false;
var days = ["monday", "tuesday", "wednesday", "thursday"];
var pages = {};
var pagesToAddLater = [];

$(document).ready(function() {
  showLoading();
  fetchData( function() {
    if( authenticateUser() == false ) return;
    dataLoaded = true;
    for(let page of pagesToAddLater) {
      addPage(page);
    }

    checkSelector();
    doneLoading();
  });

  let checkSelector = function() {
    hidePages();
    showPage( $("#selector").val() );
  };

  $("#selector").on("change", checkSelector);
});