/**
 * This file is intended to be used across all sites, if you update this file
 * make sure all instances where this is used is also updated correctly
 */

/**
 * Convert the unix time stamp in to a human readable string
 */
function unixToHuman(unixTimestamp, args={}) {
  // Create a new Date object by multiplying the Unix timestamp by 1000 (to convert from seconds to milliseconds)
  const date = new Date(unixTimestamp * 1000);

  const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

  // Extract the various components of the date and time
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based, so add 1 and pad with leading zero
  const day = String(date.getDate()).padStart(2, '0');
  let hours = date.getHours();
  const minutes = String(date.getMinutes()).padStart(2, '0');

  // Convert hours to 12-hour format and set AM/PM
  let ampm = 'am';
  if (hours >= 12) {
    ampm = 'pm';
    if (hours > 12) hours -= 12;
  }

  // Get the day of the week as a string
  const weekName = dayNames[date.getDay()];

  if(hours < 10) hours = "0" + hours;

  // Create a formatted timestamp string
  let timestamp = `${hours}:${minutes}${ampm}, ${weekName} ${month}/${day}/${year}`;
  return timestamp;
}

function unixToDuration(unixTime, decimalMode = false, showZero = false) {
  if(showZero == false)
    if(unixTime == undefined || unixTime == 0) return "";

  let time = unixTime;
  let hours = time/60/60; // Hours

  if(decimalMode == true)
    // return Math.floor(hours * 100) / 100;
    return hours.toFixed(2);

  let mins = Math.round((hours%1)*60); // Mins
  let minsString = (mins < 10)? "0"+mins: mins;
  let timeStr = Math.floor(hours) + ":" + minsString;

  return timeStr;
}


async function post(url, args = {}, returnFunction = null) {

  let response;
  try {
    response = await $.ajax({ url: url, method: "POST", data: args });
  } catch (error) {
    console.error(
      "An error occurred during the AJAX request",
      "\nURL: "+url,
      "\nArgs: ", args,
      "\nError: ", error
    );
    return null;
  }

  // Parse and return
  let data;
  try {
    data = JSON.parse(response);
  } catch(error) {
    console.error(
      "Response String:", "'"+response+"'",
      "\nResponse Length:", response.length,
      "\nURL: "+url,
      "\nArgs: ", args,
      // "\n\n", error, error.stack
    );
    if(response.length > 0) {
      console.log(response);
      displayError(response);
    }

    return null;
  }

  if(returnFunction != null) returnFunction(data);
  return data;
}


/**
 * Displays a message on the screen as a notification with the title of error
 * @param {*} string
 */
function displayError(string) {
  displayAlert({
    title: "An Error has Occurred",
    html : string
  });
}


/**
 * Displays an alert on the screen as a notification for the user to see
 * @param {obj} args
 *     - title {string}: The title that will be shown up top
 *     - html  {string}: The html that will be added in a div element
 *     - text  {string}: The message that will be showen in a <p>
 *     - jObj  {jquery document element}: will be appended to the notification
 * @returns {jObj} The notification that is created
 */
function displayAlert(args) {
  let div = $("<div>", {class: "notification induce-blur"});

  if("title"   in args) div.append($("<h2>" ).text(args.title));
  if("html"    in args) div.append($("<div>").html(args.html ));
  if("text"    in args) div.append($("<p>"  ).text(args.text ));
  if("jObj"    in args) div.append(args.jObj);

  // Add a close button so the user isnt stuck
  div.append(
    $("<center>").append(
      $("<button>")
        .text("OK")
        .click(async ()=>{
          if("onClose" in args) await args.onClose();
          div.remove();
          checkBlur();
        })
    )
  );
  $("body").append(div);
  checkBlur();
  return div;
}


/**
 * Changes the blur overlay on the screen according to the input variable
 */
function blurScreen(state=true) {
  const blurOverlay = document.getElementById('blur-overlay');
  if(blurOverlay == null) return;

  if(state == true) {
    blurOverlay.style.display = "block";
    return;
  }
  blurOverlay.style.display = "none";
}


/**
 * Checks if any of the elements on the screen has the CSS class .induce-blur if
 * there is an element that has this class then we should blur the screen
 */
function checkBlur() {
  if ($('.induce-blur').length > 0) {
    blurScreen(true);
    return;
  }
  blurScreen(false);
}

/**
 *
 * @param {obj} data
 * @param {obj} args
 * @returns {JQuery Obj}
 */
function mktable(data, args = {}) {
  // Check that data is an array, if it is not convert it to an array
  if (typeof data !== 'object' || Array.isArray(data) || data === null) {
  } else {
    // Convert object to array
    data = Object.values(data);
  }

  let ht = ("headerNames" in args)? args.headerNames: [];
  let varTriggers = ("triggers" in args)? args.triggers: [];
  let ignore = ("ignore" in args)? args.ignore: [];

  let table = $("<table>");
  let header = $("<tr>");

  // Grab header info
  let headers = [];
  for(let index in data) {
    let entry = data[index];
    for(let key in entry) {
      if((headers.indexOf(key) > -1))
        continue;

      if(ignore.indexOf(key) > -1)
        continue;

      // Try to convert data name in to human names
      header.append($("<th>").text( (key in ht)? ht[key]: key));

      headers.push(key);
    }
  }

  // If the header length is zero why print the header?
  if(header.html().length > 0) table.append(header);

  // Add data to the table
  for(let index in data) {
    let entry = data[index];
    // Create a row for each entry
    let row = $("<tr>");

    if("rowTrigger" in args) {
      args["rowTrigger"](row, entry);
    }

    // Check for if the row has a click action
    if("onRowClick" in args) {
      row.click( function() {args["onRowClick"](entry);} );
      row.addClass("clickable");
    }

    if("onContext" in args) {
      // Add a listener
      row.on("contextmenu", function(e) {
        // Highlight the row
        row.addClass("highlight");

        // Prevents default context menu
        e.preventDefault();

        // Create the context menu and apply it to the document
        var contextMenu = $("<div>").addClass("context-menu");
        contextMenu.css({ display: "block",  left: e.pageX,  top: e.pageY });
        $(document.body).append(contextMenu);

        // Add items to the context menu according to the args
        for(let key in args.onContext) {
          // Grabs the function that will be called when its clicked
          let fun = args.onContext[key];

          // Create and append the element to the menu
          contextMenu.append(
            $("<p>")
              .text(key)
              .click(function(){fun(entry);})
          );
        }

        // Add listener so that the context menu is removed when the use is done with it
        $(document).on("click contextmenu", function() {
          contextMenu.remove();
          $(document).off("click");
          row.removeClass("highlight");
        });

        return false;
      });
    }

    for(let head of headers) {
      let td = $("<td>"); row.append(td);

      if(head in entry == false) continue;

      let html = "";

      value = entry[head];
      if((typeof value === 'object' && value !== null) || Array.isArray(value)) {
        for(let index in value) {
          rowString = (Array.isArray(value))? value[index]: index+": "+value[index];
          html += rowString+"<br>"
        }
      } else {
        html += value;
      }

      // Run custom actions for items with a specific header
      for(let t of varTriggers) {
        if( t.case.indexOf(head) <= -1 )
          continue;

        html = t.func(entry[head]);
      }

      td.html( html );
    }

    if(row.html().length > 0) table.append(row);
  }

  let div = $("<div>");

  if("title" in args == true) {
    div.append($("<h2>", {style: "text-align:center; margin-bottom: 0em;"}).text(args.title))
  }

  // Check if table is empty, if it is show a message
  if(data == undefined || data.length <= 0 || table.find('tr').length <= 0) {
    div.append( $("<p>", {style: "text-align: center; margin-top: 0em;color:light-grey;"}).text("No Data") );
    return div;
  }

  // Add the table to the div
  div.append(table);

  // Add an export to csv function
  if('showExport' in args == true) {
    div.append(
      $("<center>", {style: "margin-top: .5em; margin-bottom: .5em;"}).append(
        $("<button>").click(function() {
          TableToExcel.convert(table[0]);
        }).text("Export CSV")
      )
    );
  }
  return div;
}