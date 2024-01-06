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
  let timestamp = `${hours}:${minutes}${ampm}, <span class="${weekColor}">${weekName}</span> ${month}/${day}/${year}`;
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
    console.error("An error occurred during the AJAX request:", error);
    return null;
  }

  // Parse and return
  try {
    let data = JSON.parse(response);
    if(returnFunction != null) returnFunction(data);
    return data;
  } catch(error) {
    if(response.length > 0) {
      console.log(response);
      displayError(response);
    }

    if(returnFunction != null) returnFunction(null);
    return null;
  }
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