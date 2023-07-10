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


/**
 * A quick way to create an element with innerHTML set
 *
 * @param {string} type
 * @param {string} innerHTML
 * @returns The created element
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

  console.time("fetchData");
  ajaxJson("/ajax/fetch-data.php", function(obj) {
    console.log(obj);

    // Create an array from the json in data
    let forms = [];
    for(let formId in obj.forms) {
      forms.push( obj.forms[formId] );
    }

    // Sort forms
    forms = sortForms(forms);

    // Set forms back to the object
    obj.forms = forms;

    data = obj;
    console.log(data);
    console.timeEnd("fetchData");

    returnFunction(obj);
  }, args);
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

$(document).ready(function() {
  // Recenter notifications on window resize or scroll
  $(window).resize(centerNotification);
  $(window).scroll(centerNotification);
});

var data = null;
var code = false;