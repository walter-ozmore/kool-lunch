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

// Not sure if this is used anymore
// function drawFormAlert(index) {
//   let formEle = showForm(index);
//   formEle.classList.add("content");
//   document.getElementById("forms").appendChild(formEle);
//   alertTo(formEle);
// }

var days = ["monday", "tuesday", "wednesday", "thursday"];

$(document).ready(function() {
  let checkSelector = function() {
    let selected = $("#selector").val();
    var pages = ["stats-page", "forms-page"];

    // Hide all pages
    for(let pageId of pages) {
      document.getElementById(pageId).style.display = "none";
    }

    document.getElementById(selected).style.display = "block";
  };

  $("#selector").on("change", checkSelector);

  checkSelector();

  // Grab the data from the database
  fetchData( function() {
    if( authenticateUser() == false ) return;

    drawSummary();
    drawForms();
  });
});