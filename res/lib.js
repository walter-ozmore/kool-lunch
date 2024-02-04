
/**
 * Opens up a notification window that shows the individual and allows the user
 * to see where the user is linked too. The user also has access to edit the user
 * or even delete them as long as they are not tied to another table
 *
 * @param {obj} individualData
 */
function inspectIndividual(individualData) {
  let div = $("<div>", {class: "notification induce-blur"});
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
  div.append(
    $("<h2>").text("Inspect Individual"),
    divGrid,
  );

  // Apply to div grid
  divGrid.append(
    $("<label>").text("Individual Name:"), $("<p>").text(individualData.individualName),
  );

  // Show the individual's contact information if they have it
  if(individualData.phoneNumber != null) divGrid.append($("<label>").text("Phone Number:"), $("<p>").text(individualData.phoneNumber));
  if(individualData.email != null) divGrid.append($("<label>").text("Email:"), $("<p>").text(individualData.email));
  if(individualData.facebookMessenger != null) divGrid.append($("<label>").text("Messenger:"), $("<p>").text(individualData.facebookMessenger));
  divGrid.append(
    $("<label>").text("Prefered Contact:"),
    $("<p>").text((individualData.preferredContact == null)? "None Specified": individualData.preferredContact),
  );

  // Create the delete button ahead of time and enabled it later
  let deleteButton = $("<button>", {disabled: true})
    .text("Delete")
    .click(async ()=>{post("/ajax/admin.php", {
      function: 8,
      individualID: individualData.individualID
    }, (data)=>{
      console.log(data);
      if(data.code == 0) location.reload();
    })})
  ;

  // Add links for the user, like if they are atttached to a form or something
  post("/ajax/admin.php", {
    function: 7,
    individualID: individualData.individualID
  }, (data)=>{
    let formIDEle = $("<p>");
    let volunteerFormIDEle = $("<p>");
    divGrid.append($("<label>").text("Signup Forms:"), formIDEle);
    divGrid.append($("<label>").text("Volunteer Forms:"), volunteerFormIDEle);

    console.log("Data:", data)

    // Create links for each form that is clickable
    for(let form of data.Form) {
      if(form.formID == null) continue;

      formIDEle.append(
        $("<a href=''>", {style: "display: inline; margin-right: .5em;", class: "clickable"})
          .text(form.formID)
          .click(async ()=>{
            // Fetch and inspect the form
            let returnForm = await post("/ajax/admin.php", {
              function: 3,
              formID: form.formID
            });

            inspectForm(returnForm);
          })
      );
    }

    // Create links for each volunteer form that is clickable
    for(let form of data.FormVolunteer) {
      if(form.volunteerFormID == null) continue;
      volunteerFormIDEle.append(
        $("<a>", {style: "display: inline; margin-right: .5em;", class: "clickable"})
          .text(form.volunteerFormID)
          .click(async ()=>{
            // Fetch and inspect the form
            let volunteerForm = await post("/ajax/admin.php", {
              function: 1,
              volunteerFormID: form.volunteerFormID
            });

            inspectVolunteerForm(volunteerForm);
          })
      );
    }

    if(data.FormVolunteer.length + data.Form.length <= 0) {
      deleteButton.prop("disabled", false);
    }
  });

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append(
    deleteButton,
    $("<button>")
      .text("OK")
      .click(async ()=>{ div.remove(); checkBlur(); }),
  ));
  $("body").append(div);
  checkBlur();
}


function inspectVolunteerForm(formData) {
  let div = $("<div>", {class: "notification induce-blur"});
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
  div.append(
    $("<h2>").text("Inspect Volunteer Form"),
    divGrid,
  );

  // Apply to div grid
  divGrid.append(
    $("<label>").text("Individual Name:"), $("<p>").text(formData.individualName),
    $("<label>").text("Time Submitted:"), $("<p>").text(unixToHuman(formData.timeSubmitted)),
  );

  if(formData.phoneNumber != null) divGrid.append($("<label>").text("Phone Number:"), $("<p>").text(formData.phoneNumber));
  if(formData.email != null) divGrid.append($("<label>").text("Email:"), $("<p>").text(formData.email));
  if(formData.facebookMessenger != null) divGrid.append($("<label>").text("Messenger:"), $("<p>").text(formData.facebookMessenger));

  // Add checkbox stuff
  let checkbox;
  checkbox = $("<input>", {type: "checkbox", disabled: true})
  if(formData.weekInTheSummer == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Week in the summer"), $("<br>"), );

  checkbox = $("<input>", {type: "checkbox", disabled: true})
  if(formData.bagDecoration == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Bag Decoration"), $("<br>"), );

  checkbox = $("<input>", {type: "checkbox", disabled: true})
  if(formData.fundraising == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Fundraising"), $("<br>"), );

  checkbox = $("<input>", {type: "checkbox", disabled: true})
  if(formData.supplyGathering == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Supply Gathering"), $("<br>"), );

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append(
    $("<button>")
      .text("Delete")
      .click(async ()=>{
        post("/ajax/admin.php", {
          function: 6,
          formID: formData.volunteerFormID
        }, (obj)=>{
          if(obj.code == 0) location.reload();
        });
      }),
    $("<button>")
      .text("OK")
      .click(async ()=>{ div.remove(); checkBlur(); }),
  ));
  $("body").append(div);
  checkBlur();
}


async function inspectForm(formData) {
  console.log("Form", formData);

  // Fetch some fresh data to work with
  let freshFormData = await post("/ajax/admin.php", {
    function: 3,
    formID: formData.formID
  });

  console.log("freshFormData", freshFormData);
  if(freshFormData == null) {
    displayError("Missing fresh form data");
    return;
  }


  // TODO: Fetch fresh form data because display data doesn't divide up pickup days and such

  let div = $("<div>", {class: "notification induce-blur"});
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
  div.append(
    $("<h2>").text("Inspect Form"),
    divGrid,
  );

  // Apply to div grid
  divGrid.append(
    $("<label>").text("Form ID:"), $("<p>").text(formData.formID),
    $("<label>").text("Time Submitted:"), $("<p>").text(unixToHuman(formData.timeSubmitted)),
    $("<label>").text("Location:"), $("<p>").text(formData.location),
    $("<label>").text("lunchesNeeded:"), $("<p>").text(formData.lunchesNeeded),
  );

  // Add enabled checkbox
  divGrid.append(
    $("<label>").text("Enabled:"),
    $("<input>", {type: "checkbox"})
      .prop("checked", (freshFormData.isEnabled == 1)? true: false)
      .change(function() {
        // Prevent checkbox spam
        $(this).prop("disabled", true);

        // Check if the checkbox is checked
        let setValue = $(this).prop("checked");

        // Send the data to the server
        post("/ajax/admin",
          {function: -1, setValue: setValue},
          ()=>{
            // TODO: Set the checkbox to the returned value that the server has
            $(this).prop("disabled", false);

            // Failed, set checkbox back
            $(this).prop("checked", !setValue);
          }
        );
      })
  )

  // Add date checkboxes
  for(let dateStr of ["Mon", "Tue", "Wed", "Thu"]) {
    divGrid.append(
      $("<label>").text("Pickup "+dateStr+":"),
      $("<input>", {type: "checkbox"})
        .prop("checked", (freshFormData["pickup"+dateStr] == 1)? true: false)
        .change(function() {
          // Prevent checkbox spam
          $(this).prop("disabled", true);

          // Check if the checkbox is checked
          let setValue = $(this).prop("checked");

          // Send the data to the server
          post("/ajax/admin",
            {function: -1, setValue: setValue, dateStr: dateStr},
            ()=>{
              // TODO: Set the checkbox to the returned value that the server has
              $(this).prop("disabled", false);

              // Failed, set checkbox back
              $(this).prop("checked", !setValue);
            }
          );
        })
    )
  }

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append(
    $("<button>")
      .text("OK")
      .click(async ()=>{ div.remove(); checkBlur(); }),
  ));
  $("body").append(div);
  checkBlur();
}


// Old code

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
      console.log(str);

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

var data = null;
var code = false;