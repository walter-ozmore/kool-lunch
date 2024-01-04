function blink() {
  // console.log("Blink");
  closeEyes();
  setTimeout(function() {openEyes(); }, 200);
}

function closeEyes() {
  let image = document.getElementById('headerImage');
  if( image == null ) return;
  image.src = "/res/images/headerBlink.png";
}

function openEyes() {
  let image = document.getElementById('headerImage');
  if( image == null ) return;
  image.src = "/res/images/header.png";
}

function toggleHamburgerMenu() {
  var x = document.getElementById("links");
  if( x.style.display == "none" )
    x.style.display = "block";
  else
    x.style.display = "none";
}

function addFaq(question, answer) {
  let faqDiv = document.getElementById("faq");
  let div = document.createElement("div");
  div.classList.add("faqElement");

  let answerEle = document.createElement("p");
  answerEle.innerHTML = answer;
  answerEle.classList.add("answer");
  answerEle.style.display = "none";

  let questionDiv = document.createElement("p");
  questionDiv.classList.add("question");
  questionDiv.style.display = "flex";
  questionDiv.style.justifyContent = "space-between";
  questionDiv.addEventListener("click", function(e) {
    if( answerEle.style.display == "none" )
      answerEle.style.display = "block";
    else
      answerEle.style.display = "none";
  }, false);

  let questionEle = document.createElement("p");
  questionEle.innerHTML = question;
  questionDiv.appendChild(questionEle);

  let button = document.createElement("p");
  button.innerHTML = "+";
  button.style.textAlign = "right";
  questionDiv.appendChild(button);

  div.appendChild(questionDiv);
  div.appendChild(answerEle);

  faqDiv.appendChild(div);
}

/**
 * @deprecated
 */
function ajaxJson(url, fun, args={}) {
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState != 4 || this.status != 200) return;
    let obj = {};
    try {
      obj = (this.responseText.length > 0)? JSON.parse(this.responseText) : {};
    } catch {
      console.error(this.responseText);
      return null;
    }

    if(fun != null)
      fun(obj);
  };

  xhttp.open("POST", url, true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("q="+JSON.stringify(args));
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
 * A quick way to create an element with innerHTML set
 *
 * @param {string} type
 * @param {string} innerHTML
 * @returns The created element
 *
 * @deprecated
 */
function mkEle(type, innerHTML=null) {
  let ele = document.createElement(type);
  if(innerHTML !== null)
    ele.innerHTML = innerHTML;
  return ele;
}

function timeConverter(UNIX_timestamp){
  let a = new Date(UNIX_timestamp * 1000);
  let months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  let year = a.getFullYear();
  let month = months[a.getMonth()];
  let date = a.getDate();
  let hour = a.getHours();
  let min = a.getMinutes();
  let ampm = hour >= 12 ? 'pm' : 'am';
  hour = hour % 12;
  hour = hour ? hour : 12; // convert 0 to 12
  let time = `${hour}:${(min<10? '0': '') + min + ampm} ${month} ${date}, ${year}`;
  return time;
}


/**
 * Fetches data from the database according to the user's permissions
 */
function fetchData(returnFunction = null, args = {}) {
  if(data != null)
    returnFunction(data);

  let url = "/ajax/fetch-data.php";

  $.ajax({
    type: "POST",
    url: url,
    data: JSON.stringify(args),
    contentType: "application/json",
    success: function(str) {
      // Decode JSON
      let obj = JSON.parse(str);

      // Set the data to our object
      data = obj;

      sortData();

      // Call return function if its available
      if(returnFunction != null) returnFunction(data);
    }
  });
}


/**
 * Sorts the form that is in the data
 */
function sortData() {
  // Create an array from the json in data
  let forms = [];
  for(let formId in data.forms) {
    forms.push( data.forms[formId] );
  }

  // Sort forms
  forms = sortForms(forms);

  // Set forms back to the object
  data.forms = forms;
}


/**
 * Sorts the given array and returns the array
 */
function sortForms(forms) {
  let compare = function(a, b) {
    return a.individuals[0]["IndividualName"] < b.individuals[0]["IndividualName"];
  };

  // Bubble sort
  for(let i = 0; i < forms.length; i++) {
    for(let j = 0; j < forms.length - i - 1; j++) {
      if( compare(forms[j+1], forms[j]) ) {
        // Swap
        [forms[j+1], forms[j]] = [forms[j], forms[j+1]];
      }
    }
  }
  return forms;
}

function getAdults(form) {
  let individuals = [];
  for(let ind of form["individuals"]) {
    if(ind["isAdult"] == 1)
      individuals.push(ind);
  }
  return individuals;
}


/**
 * Alert the user to the element by scrolling to it and flashing the boxShadow
 *
 * @param {HTMLElement} ele
 */
function alertTo(ele) {
  // Scroll to the div
  ele.scrollIntoView({ behavior: "smooth" });

  let toggle = false;
  // ele.style.border = "2px solid yellow";
  ele.style.boxShadow = '0 0 5px yellow';
  const intervalId = setInterval(() => {
    ele.style.boxShadow = ((toggle)? "": "0 0 5px yellow");
    toggle = !toggle;
  }, 275);

  setTimeout(() => {
    clearInterval(intervalId);
    ele.style.boxShadow = "";
  }, 2000);
}

function createLogin() {
  let noti = $("<div>");

  noti
    .addClass("content")
    .addClass("notification")
  ;

  if( code ) {
    // Access code
    noti
      .append( $("<h2>").text("Access Code") )
      .append(
        $("<center>")
          .append( $("<input>", {type: "number", name: "uname"}) )
          .append( $("<br>") )
          .append( $("<button>").text("Submit") )
      )
  } else {
    noti
      .append($("<h2>").text("Login").css("margin-bottom", "1em"))
      .append(
        $("<div>", {id: "login"})
          .addClass("grid")
          .append( $("<label>").text("Username") )
          .append( $("<input>", {type: "text", name: "uname"}) )
          .append( $("<label>").text("Password") )
          .append( $("<input>", {type: "password", name: "pword"}) )
      )
      .append(
        $("<center>")
          .append( $("<input>", {type: "checkbox", name: "sli"}) )
          .append( $("<label>").text("Remember me") )
      )
      .append(
        $("<center>").append(
          $("<button>")
            .text("Login")
            .on("click", function() {
              account_login(function() {window.location.reload();})
            })
        )
      )
  }

  $("body").append(noti);
  centerNotification();
}

function authenticateUser() {
  // If the user does not have permission then show error message
  if(data === undefined || data.code != 0) {
    createLogin();
    return false;
  }

  // Remove the login div and reset the stats div if they exist
  let loginEle = document.getElementById("login");
  if( loginEle != undefined ) {
    document.getElementById("stats").innerHTML = "";
    document.body.removeChild( loginEle.parentElement );
  }

  return true;
}

function centerNotification() {
  var notification = $('.notification');
  var windowWidth = $(window).width();
  var windowHeight = $(window).height();
  var notificationWidth = notification.outerWidth();
  var notificationHeight = notification.outerHeight();

  var leftPosition = (windowWidth - notificationWidth) / 2;
  var topPosition = (windowHeight - notificationHeight) / 2;

  notification.css({
    left: leftPosition + 'px',
    top: topPosition + 'px'
  });
}

function showLoading() {
  let noti = $("<div>", {id: "loading"});

  noti
    .addClass("content")
    .addClass("notification")
    .append( $("<h2>").text("Loading") )
    .append(
      $("<p>")
        .css("text-align", "center")
        .text( loadingMessages[Math.floor(Math.random() * loadingMessages.length)] )
    )
  ;

  $("body").append(noti);
  centerNotification();
}

function doneLoading() {
  $("#loading").remove();
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


function mktable(data, args = {}) {
  // Data store to convert data names to human names
  let ht = {
    timeSubmitted: "Submit Time",
    weekInTheSummer: "For a Week",
    bagDecoration: "Bag Decoration",
    fundraising: "Fundraising",
    supplyGathering: "Supplies"
  };
  let varTriggers = [
    {
      case: ["weekInTheSummer", "bagDecoration", "fundraising", "supplyGathering"],
      func: function(data) { return (data == "1")? "Yes": "No"; }
    },
    {
      case: ["timeSubmitted"],
      func: function(data) { return "Missing function"; }
    }
  ];
  let ignore = ["uid", "entryID", "volunteerFormID"];

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

      let html = entry[head];

      // Run custom actions for items with a specific header
      for(let t of varTriggers) {
        console.log( t, head, t.case.indexOf(head) )
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

  div.append(table);
  return div;
}

var data = null;
var code = false;