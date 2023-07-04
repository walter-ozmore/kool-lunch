/**
 * Draws an overall summary that contains no PII
 */
function drawSummary() {
  // Calculate stats client side
  let counter = calculateStats();

  // Create lists of information to track
  let locations = data["locations"];
  let display = $("<div>").addClass("content");
  $("#stats-page").append(display);

  // drawObjectToHTML(counter);

  for(let day of days) {
    let table = $("<table>")
      .append($("<tr>")
        .append( $("<th>").text("Location") )
        .append( $("<th>").text("Lunches" ) )
        .append( $("<th>").text("Allergies" ) )
      )
    ;

    let marginTop = (day == "monday")? "0em" : "2.5em";
    let divEle = $("<div>")
      .append(
        $("<h2>")
          .text(day)
          .css("margin", `${marginTop} 0em .5em 0em`)
      )
      .append( table )
    ;



    for(let location of locations) {
      table.append($("<tr>")
        .append( $("<td>").text(location ) )
        .append( $("<td>").text(counter[day][location].lunches) )
        .append( createAllergyEle(counter[day][location].allergies) )
      );
    }

    table.append($("<tr>")
      .append( $("<td>").text("Total") )
      .append( $("<td>").text(counter[day].lunches) )
      .append( createAllergyEle(counter[day].allergies) )
    );

    display.append(divEle);
  }

  for(let x of data.counts) {
    let str = `${x.date}: ${x.count}`;
    $("#stats").append($("<p>").text(str))
  }

}


/**
 * Draws all the forms loaded in the form area
 */
function drawForms() {
  // Draw forms
  for(let index in data["forms"]) {
    let formEle = createFormElement(index, false);
    $("#forms-page").append(formEle);
  }
}


function drawFormAlert(index) {
  let formEle = showForm(index);
  formEle.classList.add("content");
  document.getElementById("forms").appendChild(formEle);
  alertTo(formEle);
}

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

        console.log( counter );
      }
    }

    for(let day of form["pickupDays"]) {
      counter[day].lunches += Number(form["lunchesNeeded"]);
      counter[day][location].lunches += Number(form["lunchesNeeded"]);
    }
  }
  return counter;
}

function createAllergyEle(objList) {
  let allergyEle = $("<td>");

  for(let obj of objList) {
    allergyEle.append(
      $("<p>")
        .text(obj.allergies)
        .click(function() {
          createFormElement(obj.formIndex);
        })
    );
  }

  return allergyEle;
}

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