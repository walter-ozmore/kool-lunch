function blink() {
  console.log("Blink");
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
      obj = JSON.parse(this.responseText);
    } catch {
      console.error(this.responseText);
      return null;
    }
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
  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + (min < 10 ? '0' : '') + min + ' ' + ampm;
  return time;
}

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