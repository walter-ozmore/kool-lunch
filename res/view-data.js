function drawFormAlert(formId) {
  let formEle = showForm(formId);
  formEle.classList.add("content");
  document.getElementById("form-area").appendChild(formEle);
  alertTo(formEle);
}

function authenticateUser() {
  // If the user does not have permission then show error message
  if(data === undefined || data.code != 0) {
    createLogin();
    return;
  }

  // Remove the login div and reset the stats div if they exist
  let loginEle = document.getElementById("login");
  if( loginEle != undefined ) {
    document.getElementById("stats").innerHTML = "";
    document.body.removeChild( loginEle.parentElement );
  }
}

function calculateStats() {
  // Create counter object for the stats
  let counter = {};

  // Create lists of information to track
  let days = ["Monday", "Tuesday", "Wednesday", "Thursday"];
  let locations = data["locations"];

  // Create counter obj

  for(let day of days) {
    counter[day] = {};
    counter[day].lunches = 0;
    for(let location of locations) {
      counter[day][location] = {};
      counter[day][location]["lunches"] = 0;
    }
  }

  // Go though the data and fill up the counter object
  for(let form of data["forms"]) {
    let location = form["Location"];

    for(let ind of form["individuals"]) {
      // Individual is adult
      if(ind.IsAdult == 1) {
        continue;
      }

      // Individual is child
      let allergies = ind["Allergies"];

      for(let day of days) {
        if( form["Pickup"+day] == 1 ) {
          counter[day].lunches += 1;
          counter[day][location].lunches += 1;

          // if(allergies !== undefined && allergies.length > 0)
          //   day.allergies.push({allergies: allergies, formId: form["FormId"]});
        }
      }
    }
  }
  return counter;
}

function drawStats(counter) {
  // Create lists of information to track
  let days = ["Monday", "Tuesday", "Wednesday", "Thursday"];
  let locations = data["locations"];

  console.log( counter );
  // drawObjectToHTML(counter);

  for(let day of days) {
    let table = $("<table>")
      .append($("<tr>")
        .append( $("<th>").text("Location") )
        .append( $("<th>").text("Lunches" ) )
      )
    ;

    let divEle = $("<div>")
      .append( $("<h2>").text(day) )
      .append( table )
    ;



    for(let location of locations) {
      // divEle.appendChild( mkTab(location+": "+counter[day][location].lunches, 1) );
      // $(divEle).append( mkTab(location+": "+counter[day][location].lunches, 1) );

      table.append($("<tr>")
        .append( $("<td>").text(location ) )
        .append( $("<td>").text(counter[day][location].lunches) )
      );
    }

    table.append($("<tr>")
      .append( $("<td>").text("Total") )
      .append( $("<td>").text(counter[day].lunches) )
    );

    $("#stats").append(divEle);
  }
}

function mkTab(innerHTML, tabs=0) {
  let ele = mkEle("p", innerHTML);
  ele.style.marginLeft = tabs*2 + "em";
  // document.getElementById("stats").appendChild(ele);
  return ele;
}

function draw() {
  // ajaxJson("/ajax/fetch-data.php", drawData);
  fetchData( function() {
    let counter = calculateStats();
    drawStats(counter);

    // Draw forms
    for(let index in data["forms"]) {
      let formEle = createFormElement(index, false);
      document.getElementById("form-area").appendChild(formEle);
    }
  });
}

var data;
draw();