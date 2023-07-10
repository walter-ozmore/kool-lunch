addPage({
  id: "stats-page",
  name: "Overview",
  init: function() {
    let page = $("#stats-page");

    /**
     * Draws the given object in to a table with clickable links
     *
     * @param {*} objList
     * @returns
     */
    let createAllergyEle = function(objList) {
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

    // Calculate stats client side
    let counter = calculateStats();

    // Create lists of information to track
    let locations = data["locations"];
    let display = $("<div>").addClass("content");
    page.append(display);

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

    let stats = $("<div>", {id: "stats"});
    page.append( stats );

    stats.append(
      $("<div>")
        .addClass("content")
        .append( $("<canvas>", {id: "nameofgraph"}) )
    );


    // Draw the counts per day
    var lunchData = [];
    for(let x of data.counts) {
      lunchData.push( {x: x.date, y: x.count} );
    }

    // Draw graphs
    const DateTime = luxon.DateTime; // Reference the DateTime class from Luxon

    var scatterChart = new Chart("nameofgraph", {
      type: 'scatter',
      data: {
        datasets: [{
          label: 'Recorded',
          data: lunchData,
          showLine: true,
          // borderColor: 'rgb(100, 100, 255)',
          pointRadius: 5,
          backgroundColor: "rgb(155, 0, 0)",
          borderColor: "rgb(100, 0, 0)"
        },
        {
          label: 'Expected',
          data: {},
          showLine: true,
          // borderColor: 'rgb(100, 100, 255)',
          pointRadius: 5,
          backgroundColor: "rgb(0, 155, 0)",
          borderColor: "rgb(0, 100, 0)"
        }
        ],
      },
      options: {
        scales: {
          x: {
            type: 'time',
            time: {
              parser: function(value) {
                return DateTime.fromFormat(value, 'MMM dd');
              },
              unit: 'day',
              displayFormats: {
                day: 'MMM d' // Modify the format to display only month and day
              }
            }
          }
        }
      }
    });


  }
});