/**
 * This file is intended to be used across all sites, if you update this file
 * make sure all instances where this is used is also updated correctly
 */

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

  if("title" in args) div.append($("<h2>" ).text(args.title));
  if("html"  in args) div.append($("<div>").html(args.html ));
  if("text"  in args) div.append($("<p>"  ).text(args.text ));
  if("jObj"  in args) div.append(args.jObj);

  // Add a close button so the user isnt stuck
  div.append(
    $("<center>").append(
      $("<button>")
        .text("OK")
        .click(()=>{div.remove();checkBlur();})
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