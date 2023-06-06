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
  var a = new Date(UNIX_timestamp * 1000);
  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var year = a.getFullYear();
  var month = months[a.getMonth()];
  var date = a.getDate();
  var hour = a.getHours();
  var min = a.getMinutes();
  var ampm = hour >= 12 ? 'pm' : 'am';
  hour = hour % 12;
  hour = hour ? hour : 12; // convert 0 to 12
  // var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + (min < 10 ? '0' : '') + min + ' ' + ampm;
  var time = `${hour}:${(min<10? '0': '') + min + ampm} ${month} ${date}, ${year}`;
  return time;
}


/**
 * Fetches data from the database according to the user's permissions
 */
function fetchData(returnFunction = null) {
  if(data != null)
    returnFunction(data);

  console.log("Fetching data");
  ajaxJson("/ajax/fetch-data.php", function(obj) {
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

    returnFunction(obj);
  });
}

function compare(a, b) {
  return a.individuals[0]["IndividualName"] < b.individuals[0]["IndividualName"]
}


/**
 * Sorts the given array and returns the array
 */
function sortForms(forms) {
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
  // Create login page
  let loginDiv = mkEle("div");
  loginDiv.classList.add("content");
  loginDiv.classList.add("notification");
  document.body.appendChild(loginDiv);
  loginDiv.innerHTML = `
    <h2 style="margin-bottom: 1em;">Login</h2>
    <div class="grid" id="login">
      <label>Username</label>
      <input type="text" name="uname">

      <label>Password</label>
      <input type="password" name="pword">

      <label>Stay Logged In</label>
      <input type="checkbox" name="sli">
    </div>
    <center><button onclick="account_login(function() {window.location.reload();})">Login</button></center>
  `;
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

var data = null;
var windowLoadedFunctions = [];
var windowLoaded = false;

/**
 * Runs the given function when the window loads, if the
 * window is already loaded it will just run the function
 *
 * @param {function} func
 */
function onWindowLoad(func) {
  if(windowLoaded == false) {
    windowLoadedFunctions.push(func);
    return;
  }
  func();
}


window.onload = function() {
  windowLoaded = true;
  for(let i=0; i<windowLoadedFunctions.length; i++) {
    windowLoadedFunctions[i]();
  }
};

onWindowLoad( function() {setInterval( blink(), 1000 );} );
onWindowLoad( function() {
  let height = window.screen.height;
  let width = window.screen.width;

  // document.getElementById("msg").innerHTML = window.screen.height+"x"+window.screen.width;
} );



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
  centerNotification(); // Center the notification initially

  // Recenter the notification on window resize
  $(window).resize(function() {
    centerNotification();
  });

  // Recenter the notification on scroll
  $(window).scroll(function() {
    centerNotification();
  });
});