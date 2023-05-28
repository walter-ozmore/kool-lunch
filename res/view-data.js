function drawFormAlert(formId) {
  let formEle = showForm(formId);
  formEle.classList.add("content");
  document.getElementById("form-area").appendChild(formEle);
  alertTo(formEle);
}

function calculateStats() {
  // Create counter object for the stats
  let counter = {};

  // Create lists of information to track
  let days = ["Monday", "Tuesday", "Wednesday", "Thursday"];
  let locations = data["locations"];

  // Create counter obj

  for(let day of days) {
    counter[day] = {
      lunches: 0,
      allergies: []
    };
    for(let location of locations) {
      counter[day][location] = {
        lunches: 0,
        allergies: []
      };
    }
  }

  // Go though the data and fill up the counter object
  for(let index in data["forms"]) {
    let form = data["forms"][index];
    if(form["isEnabled"] == 0) continue;

    let location = form["Location"];
    let lunchOverideAmount = form["lunchOverideAmount"];

    for(let ind of form["individuals"]) {
      // Individual is adult
      if(ind.IsAdult == 1) {
        continue;
      }

      // Individual is child
      let allergies = ind["Allergies"];

      for(let day of days) {
        if( form["Pickup"+day] == 1 ) {
          if(lunchOverideAmount == null || lunchOverideAmount <= 0) {
            counter[day].lunches += 1;
            counter[day][location].lunches += 1;
          }

          if(allergies !== undefined && allergies.length > 0) {
            let obj = {
              allergies: allergies,
              formIndex: index
            };

            counter[day].allergies.push( obj );
            counter[day][location].allergies.push( obj );
          }
        }
      }
    }

    // Override amount
    if(lunchOverideAmount == null || lunchOverideAmount <= 0)
      continue;

    lunchOverideAmount = Number(lunchOverideAmount);
    for(let day of days) {
      if( form["Pickup"+day] == 1 ) {
        counter[day].lunches += lunchOverideAmount;
        counter[day][location].lunches += lunchOverideAmount;
      }
    }
  }
  return counter;
}

function createAllergyEle(objList) {
  let allergyEle = $("<td>");

  for(let obj of objList) {
    console.log(obj);
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

function drawStats(counter) {
  // Create lists of information to track
  let days = ["Monday", "Tuesday", "Wednesday", "Thursday"];
  let locations = data["locations"];

  // console.log( counter );
  // drawObjectToHTML(counter);

  for(let day of days) {
    let table = $("<table>")
      .append($("<tr>")
        .append( $("<th>").text("Location") )
        .append( $("<th>").text("Lunches" ) )
        .append( $("<th>").text("Allergies" ) )
      )
    ;

    let divEle = $("<div>")
      .append( $("<h2>").text(day) )
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
    authenticateUser();

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