function showForm() {
  let ele = document.getElementById("understand");
  ele.parentNode.removeChild(ele);

  document.getElementById("form").style.display = "block";
  document.getElementById("topSec").style.display = "none";

  window.scrollTo(0, 0);
}

function makeid(length) {
  let result = '';
  const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  const charactersLength = characters.length;
  let counter = 0;
  while (counter < length) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
    counter += 1;
  }
  return result;
}


class Question {
  constructor(args) {
    let div = document.createElement("div");
    div.classList.add("question");
    if("id" in args) this.id = args.id;

    let questionEle = document.createElement("p");
    questionEle.innerHTML = args.question;
    div.appendChild(questionEle);

    let errorEle = document.createElement("p");
    errorEle.name = "error";
    errorEle.classList.add("error");
    div.appendChild(errorEle);

    this.errorEle = errorEle;
    this.answerElements = [];

    let input = null;
    switch(args.type) {
      case "checkbox":
        for(let x in args.options) {
          let option = args.options[x];

          let wrapper = document.createElement("div");

          let checkBox = document.createElement("input");
          checkBox.type = "checkbox";
          if("onchange" in args) { checkBox.onchange = function() { args.onchange(this) }; }
          if("value" in option) checkBox.value = option.value;
          else checkBox.value = "true";
          wrapper.appendChild(checkBox);
          this.answerElements.push(checkBox);

          let label = document.createElement("label");
          label.innerHTML = option.label;
          wrapper.appendChild(label);

          div.appendChild(wrapper);
        }
        break;

      case "radio":
        let radioName = makeid(10);

        for(let x in args.options) {
          let option = args.options[x];

          let wrapper = document.createElement("div");

          let radioOption = document.createElement("input");
          radioOption.type = "radio";
          radioOption.name = radioName;
          if("value" in option) radioOption.value = option.value;
          wrapper.appendChild(radioOption);
          this.answerElements.push(radioOption);

          let label = document.createElement("label");
          label.innerHTML = option.label;
          wrapper.appendChild(label);

          div.appendChild(wrapper);
        }
        break;

      case "number":
        input = document.createElement("input");
        input.type = "number";

        if("onchange" in args)
          input.onchange = function() { args.onchange(this) };

        div.appendChild(input);
        break;

      case "text":
        input = document.createElement("input");
        input.type = "text";

        div.appendChild(input);

        this.answerElements.push(input);
        break;
    }

    this.div = div;
    this.errorEle = errorEle;
    this.checkArgs = ("check" in args)? args.check: null;
    this.type = args.type;
  }

  // Returns true if there is an error
  check() {
    this.errorEle.style.display = "none";
    if(this.checkArgs == null) return false;

    if("required" in this.checkArgs) {

      let answerElements = this.answerElements;

      if(this.type == "checkbox" || this.type == "radio") {
        let anyAnswerFilled = false;
        for (let answer of answerElements) {
          if (answer.checked === true) { // Check if answer is not empty or just whitespace
            anyAnswerFilled = true;
            break; // Exit the loop if any answer is filled out
          }
        }

        if (anyAnswerFilled == false) {
          // Display an error message or do something else if no answer is filled out
          this.errorEle.innerHTML = this.checkArgs.required.errorMessage;
          this.errorEle.style.display = "block";
          return true; // Return an error
        }
      }


      if(this.type == "text") {
        let answer = this.answerElements[0].value;
        if(answer == undefined || answer.length <= 0) {
          // Display an error message or do something else if no answer is filled out
          this.errorEle.innerHTML = this.checkArgs.required.errorMessage;
          this.errorEle.style.display = "block";
          return true; // Return an error
        }
      }
    }
  }

  scrollTo() {
    var scrollDiv = this.div.offsetTop;
    window.scrollTo({ top: scrollDiv, behavior: 'smooth'});
  }

  getAnswer() {
    let answerElements = this.answerElements;
    switch(this.type) {
      case "radio":
        for (let answer of answerElements) {
          if(answer.checked)
            return answer.value;
        }
        return null;
      case "checkbox":
        let answers = [];
        for (let answer of answerElements) {
          if(answer.checked)
            answers.push( answer.value );
        }
        return answers;
      case "number":
        return this.answerElements[0].value;
      case "text":
        return this.answerElements[0].value;
    }
  }

  getEle() { return this.div; }
}

function renderAdults() {
  adults = [];
  let amount = document.getElementById("adultNumber").value;

  let section = document.getElementById("adults");
  section.innerHTML = "";

  for(let x=0;x<amount;x++) {
    let adultRow = [];
    let subdiv = document.createElement("div");
    subdiv.classList.add("content");

    // Adult
    question = new Question({
      "id":"name",
      "question": "Name of adult picking up lunches",
      "type": "text",
      "check": {
        "required": {"errorMessage": "A name is required"}
      }
    });
    ele = question.getEle();
    ele.classList.add("subdiv");
    subdiv.appendChild( ele );
    adultRow.push(question);

    question = new Question({
      "id":"phoneNumber",
      "question": "Phone number:",
      "type": "text",
      "check": {
        "required": {"errorMessage": "A phone number is required"},
        "phone-number": {"errorMessage": "A valid phone number is required"}
      }
    });
    ele = question.getEle();
    ele.classList.add("subdiv");
    subdiv.appendChild( ele );
    adultRow.push(question);

    section.appendChild( subdiv );

    adults.push(adultRow);
  }
}

function renderChildren() {
  children = [];
  let amount = document.getElementById("childNumber").value;

  let section = document.getElementById("children");
  section.innerHTML = "";

  for(let x=0;x<amount;x++) {
    let row = [];
    let subdiv = document.createElement("div");
    subdiv.classList.add("content");

    question = new Question({
      "id":"name",
      "question": "Name of child",
      "type": "text",
      "check": {
        "required": {"errorMessage": "A name is required"}
      }
    });
    ele = question.getEle();
    ele.classList.add("subdiv");
    subdiv.appendChild( ele );
    row.push(question);

    question = new Question({
      "id":"allergies",
      "question": "Allergies if applicable",
      "type": "text"
    });
    ele = question.getEle();
    ele.classList.add("subdiv");
    subdiv.appendChild( ele );
    row.push(question);

    question = new Question({
      "id":"allowPhotos",
      "question": "Do you allow us to post photos of your child?",
      "type": "checkbox",
      "options": [
        {"value": "1", "label": "Allow"}
      ]
    });
    ele = question.getEle();
    ele.classList.add("subdiv");
    subdiv.appendChild( ele );
    row.push(question);

    section.appendChild( subdiv );
    children.push(row);
  }
}


var adults = [];
var children = [];
var question = null;
var questions = [];

window.onload = function() {
  // showForm();

  // header = document.createElement("h2");
  // header.innerHTML = "Children";
  // document.getElementById("questions").appendChild( header );


  question = new Question({
    "id":"days",
    "question": "Choose days to pickup lunches",
    "type": "checkbox",
    "options": [
      {"value": "monday", "label": "Monday"},
      {"value": "tuesday", "label": "Tuesday"},
      {"value": "wednesday", "label": "Wednesday"},
      {"value": "thursday", "label": "Thursday"}
    ],
    "check": {
      "required": {"errorMessage": "A pickup location must be selected"}
    }
  });
  document.getElementById("questions").appendChild( question.getEle() );
  questions.push(question);


  question = new Question({
    "id":"location",
    "question": "Choose the place where you will pick up lunch",
    "type": "radio",
    "options": [
      {"value":"Simpson Park", "label": "Simpson Park"},
      {"value":"Powder Creak Park", "label": "Powder Creak Park"},
      {"value":"Pizza Hut", "label": "Pizza Hut"},
      {"value":"T.E.A.M. Center Housing Authority", "label": "T.E.A.M. Center Housing Authority"}
    ],
    "check": {
      "required": {"errorMessage": "A pickup location must be selected"}
    }
  });
  document.getElementById("questions").appendChild( question.getEle() );
  questions.push(question);


  question = new Question({
    "question": "I understand that if I do not let The Kool Lunches Program know before 10:55 that I will not be picking up lunches that day, my name will be removed untill I contact The Kool Lunches Program to begin receiving lunches again.",
    "type": "checkbox",
    "options": [
      {"label": "I Agree"}
    ],
    "check": {
      "required": {"errorMessage": "Must be selected"}
    }
  });
  document.getElementById("questions").appendChild( question.getEle() );
  questions.push(question);

  renderAdults();
  renderChildren();
}

function check() {
  let scrolled = false;
  let isError = false;

  for(row of adults   )
    for(q of row)
      if(q.check() == true) {
        if(!isError) q.scrollTo();
        isError = true;
      }

  for(row of children )
    for(q of row)
      if(q.check() == true) {
        if(!isError) q.scrollTo();
        isError = true;
      }

  for(q of questions)
    if(q.check() == true) {
      if(!isError) q.scrollTo();
      isError = true;
    }
  return isError;
}

function buildJson() {
  let json = {};

  json.general = {};
  for(q of questions) {
    json.general[q.id] = q.getAnswer();
  }

  json.adults = [];
  for(row of adults) {
    let jRow = {};
    for(q of row) {
      jRow[q.id] = q.getAnswer();
    }
    json.adults.push(jRow);
  }

  json.children = [];
  for(row of children) {
    let jRow = {};
    for(q of row) {
      jRow[q.id] = q.getAnswer();
    }
    json.children.push(jRow);
  }

  console.log(json);
  console.log( JSON.stringify(json) );
  return json;
}

/**
 * Sends the form to the server
 */
function send() {
  if( check() ) {
    document.getElementById("generalError").style.display = "block";
    return;
  } else {
    document.getElementById("generalError").style.display = "none";
  }
  document.getElementById("form").style.display = "none";
  document.getElementById("submission").style.display = "block";

  let json = buildJson();

  // Create XHR object
  let xhr = new XMLHttpRequest();

  // Set up POST request
  xhr.open("POST", "/ajax/sign-up.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // Send JSON data
  xhr.send("q=" + JSON.stringify(json));

  // Add event listener for response
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      console.log("Back");
      console.log(xhr.responseText);
    }
  }
}