/**
 * Quick way of making a inspect with values that update
 *
 * @param {jqueryDocumentObject} parentElement
 * @param {array of jsons} items
 */
function basicRowItems(parentElement, data, items) {
  for(let item of items) {
    parentElement.append($("<label>").text(item.label+":"))

    // Check to see if this type is something we reconise
    if("type" in item) {
      let ele = null;
      if(item.type == "text") {
        ele = $("<input>", {type: "text", value: data[item.key]});
      }

      if(item.type == "dropdown") {
        ele = $("<select>");

        // Add options to the select
        for(let key in item.options) {
          let value = key;
          let label = item.options[key];

          let optionEle = $("<option>", {value: value}).text(label);
          ele.append(optionEle);
        }
      }

      if(item.type == "checkbox") {
        ele = $("<input>", {type: "checkbox", style: "margin-right: auto;"});
        if(data[item.key] == "1") ele.prop('checked', true);
      }

      if(ele != null) {
        parentElement.append(ele); // Add to parent element
        // Add listener for when this element is changed
        if("apiFunction" in item && item["apiFunction"] != null && Number(item["apiFunction"]) > 0)
          ele.change(function() { updateServer($(this), item.apiFunction, item.key, item.args) });
        else
          ele.prop("disabled", true);

        continue
      }
    }

    // If the code has gotten here then no element must have been append via
    // other means, we will just print the value out
    let displayString = data[("value" in item)? item.value: item.key];

    if("href" in item) {
      parentElement.append(
        $("<a>", {href: "javascript:void(0)"})
          .text(displayString)
          .click(item.href)
      );
      continue
    }
    parentElement.append($("<p>").text(displayString));
  }
}

/**
 * Using the given element this function will send a post request to the server
 * in an attempt to update the element. The element will be disabled while the
 * post is being carried out. If the element failed to update with the server it
 * will be returned to its starting state, if it succedes its update the value
 * will be updated with the value that the server provides if it provides one.
 *
 * Maybe should be promoted??
 *
 * @param {JQuery Dom Element} ele the input or selection element that will be
 *   updated.
 * @param {int} apiFunction the function that will be called in /ajax/admin.php
 * @param {string} valueKey the object index where the value will be places in
 *  the post command.
 * @param {obj} args an object to be appended to the post variables.
 */
function updateServer(ele, apiFunction, valueKey, args={}) {
  // Prevent spam
  ele.prop("disabled", true);

  let postArgs = {
    function: apiFunction,
    ...args // Append the input args to our post args
  };

  // Grab the value of the input
  let value = ele.val();

  // Update the elemtn if its a checkbox
  if (ele.is(":checkbox"))
    value = ele.prop("checked");

  postArgs[valueKey] = value;

  // Send the data to the server
  post("/ajax/admin", postArgs, (json)=>{
    let isSuccessfull = json.code >= 100 || json.code < 200;

    // Update the value to the server value if it exists
    if(isSuccessfull && "value" in json)
      if (ele.is(":checkbox"))
        ele.prop("checked", json.value);
      else
        ele.val(json.value)

    // Failed, set input back
    if(!isSuccessfull)
      if (ele.is(":checkbox"))
        ele.prop("checked", !ele.val());
      // else
        // Somehow make this the value the input had before they updated it
        // ele.val(setValue)

    // TODO: Set the checkbox to the returned value that the server has
    ele.prop("disabled", false);
  });
}

/**
 * Opens up a notification window that shows the individual and allows the user
 * to see where the user is linked too. The user also has access to edit the user
 * or even delete them as long as they are not tied to another table
 *
 * If a number is passed in as the data then it will attempt to fetch the data
 * from the database using function
 *
 * @param {obj, string, number} individualData
 */
async function inspectIndividual(arg) {
  // Check if the given data is a string or number, if it is then go fetch the
  // real data
  let individualData = undefined;
  if (typeof arg === 'number' || typeof arg === 'string') {
    let data = await post("/ajax/admin.php", {
      function: 17,
      individualID: arg
    });
    if(data.code < 100 || data.code > 200) return;
    individualData = data["data"];
  } else {
    individualData = arg;
  }

  // Create, add and check blur of notification object
  let div = $("<div>", {class: "notification induce-blur"});
  $("body").append(div); // Add the notification to the page
  checkBlur(); // Check if the screen should be blured

  // Create a grid to align the items for style
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})

  // Create title
  div.append( $("<h2>").text("Inspect Individual"), divGrid );

  // Apply items to div grid
  basicRowItems(divGrid, individualData, [
    {label: "Individual ID"          , key: "individualID"},
    {label: "Individual Name"        , key: "individualName"   , type: "text"    , apiFunction: 24, args: {individualID: individualData.individualID}},
    {label: "Prefered Contact Method", key: "preferredContact" , type: "text"    , apiFunction: 24, args: {individualID: individualData.individualID}},
    {label: "Phone Number"           , key: "phoneNumber"      , type: "text"    , apiFunction: 24, args: {individualID: individualData.individualID}},
    {label: "Email"                  , key: "email"            , type: "text"    , apiFunction: 24, args: {individualID: individualData.individualID}},
    {label: "Messenger"              , key: "facebookMessenger", type: "text"    , apiFunction: 24, args: {individualID: individualData.individualID}},
    {label: "Remind Status"          , key: "remindStatus"     , type: "dropdown", apiFunction: -1, args: {individualID: individualData.individualID},
      options: {0:"No Remind Requested", 1:"Remind Requested", 2:"Remind Sended"} },
  ]);

  // Make buttons
  let button_delete = $("<button>", {disabled: true}).text("Delete")
    .click(async ()=>{post("/ajax/admin.php", {
      function: 8,
      individualID: individualData.individualID
    }, (data)=>{
      console.log(data);
      if(data.code != 110) {
        return
      }
      refreshPage("Individuals");
      // Close Window
      div.remove(); checkBlur();
    })})
  ;

  let button_close = $("<button>")
    .text("OK")
    .click(()=>{
      // Close Window
      div.remove(); checkBlur();
    })
  ;

  // Add links for the user, like if they are atttached to a form or something
  post("/ajax/admin.php", { function: 7, individualID: individualData.individualID },
  (json)=>{
    let data = json.data;
    let formIDEle = $("<p>");
    let volunteerFormIDEle = $("<p>");
    divGrid.append($("<label>").text("Signup Forms:"), formIDEle);
    divGrid.append($("<label>").text("Volunteer Forms:"), volunteerFormIDEle);

    // Create links for each form that is clickable
    for(let form of data.Form) {
      if(form.formID == null) continue;

      formIDEle.append(
        $("<a>", {style: "display: inline; margin-right: .5em;", class: "clickable", href: "javascript:void(0)"})
          .text(form.formID)
          .click(async ()=>{
            // Fetch and inspect the form
            let returnForm = await post("/ajax/admin.php", {
              function: 3, formID: form.formID
            });
            inspectForm(returnForm.data);
          })
      );
    }

    // Create links for each volunteer form that is clickable
    for(let form of data.FormVolunteer) {
      if(form.volunteerFormID == null) continue;
      volunteerFormIDEle.append(
        $("<a>", {style: "display: inline; margin-right: .5em;", class: "clickable", href:"javascript:void(0)"})
          .text(form.volunteerFormID)
          .click(async ()=>{
            // Fetch and inspect the form
            let volunteerForm = await post("/ajax/admin.php", {
              function: 1, volunteerFormID: form.volunteerFormID
            });

            inspectVolunteerForm(volunteerForm.data);
          })
      );
    }

    if(data.FormVolunteer.length + data.Form.length <= 0) {
      button_delete.prop("disabled", false);
    }
  });

  // Add buttons to the notification
  div.append( $("<center>").append(button_delete, button_close) );
}

async function inspectVolunteerForm(arg) {
  // Check if this data need to be fetched
  let volunteerFormID;
  if (typeof volunteerFormData === 'number' || typeof volunteerFormData === 'string') {
    volunteerFormID = arg;
  } else {
    volunteerFormID = arg.volunteerFormID;
  }
  let data = await post("/ajax/admin.php", {
    function: 1,
    volunteerFormID: volunteerFormID
  });
  if(data.code < 100 || data.code > 200) return;
  volunteerFormData = data["data"];

  // Create, add and check blur of notification object
  let div = $("<div>", {class: "notification induce-blur"});
  $("body").append(div);
  checkBlur();

  // Create a grid to align the items for style
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})

  // Create title
  div.append( $("<h2>").text("Inspect Volunteer Form"), divGrid );

  // Apply items to div grid
  basicRowItems(divGrid, volunteerFormData, [
    {label: "Vounteer Form ID"  , key: "volunteerFormID"},
    {label: "Individual Name"   , key: "individualName", href: ()=>{inspectIndividual(volunteerFormData.individualID)}},
    {label: "Time Submitted"    , value: unixToHuman(volunteerFormData.timeSubmitted)},
    {label: "Phone Number"      , key: "phoneNumber"      },
    {label: "Email"             , key: "email"            },
    {label: "Messenger"         , key: "facebookMessenger"},
    {label: "Week in the Summer", key: "weekInTheSummer"  , type: "checkbox", apiFunction: 18, args: {volunteerFormID: volunteerFormData.volunteerFormID}},
    {label: "Bag Decoration"    , key: "bagDecoration"    , type: "checkbox", apiFunction: 19, args: {volunteerFormID: volunteerFormData.volunteerFormID}},
    {label: "Fundraising"       , key: "fundraising"      , type: "checkbox", apiFunction: 20, args: {volunteerFormID: volunteerFormData.volunteerFormID}},
    {label: "Supply Gathering"  , key: "supplyGathering"  , type: "checkbox", apiFunction: 21, args: {volunteerFormID: volunteerFormData.volunteerFormID}},
  ]);

  /* Make buttons */
  let button_selectIndividual = $("<button>").text("Select Individual")
    .click(()=>searchIndividuals(async (individual)=>{
      await post("/ajax/admin.php", {
        function: 28,
        volunteerFormID: volunteerFormData.volunteerFormID,
        individualID: individual.individualID
      });
      div.remove(); checkBlur();
      inspectVolunteerForm(volunteerFormData.volunteerFormID);
    }))
  ;
  let button_delete = $("<button>").text("Delete")
    .click(async ()=>{
      post("/ajax/admin.php", {
        function: 6,
        formID: volunteerFormData.volunteerFormID
      }, (json)=>{
        if(json.code != 110) {
          /* Error */
          return;
        }
        // Success
        div.remove(); checkBlur(); // Close the window
        refreshPage("Volunteer Forms") // Reload table
      });
    })
  ;
  let button_close = $("<button>")
    .text("Close")
    .click(async ()=>{
      // Close window
      div.remove(); checkBlur();
    })
  ;

  // Add buttons to the notification
  div.append( $("<center>").append(button_selectIndividual, button_delete, button_close) );
}

async function inspectForm(formData) {
  console.log("Form data:", formData);
  // Fetch some fresh data to work with
  let tempObj = (await post("/ajax/admin.php", {
    function: 3,
    formID: formData.formID
  }));
  console.log("Temp return:", tempObj);
  let freshFormData = tempObj.data;


  if(freshFormData == null) {
    displayError("Missing fresh form data");
    return;
  }

  // Create, add and check blur of notification object
  let div = $("<div>", {class: "notification induce-blur"});
  $("body").append(div); // Add the notification to the page
  checkBlur(); // Check if the screen should be blured

  // Create a grid to align the items for style
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})

  // Create title
  div.append( $("<h2>").text("Inspect Form"), divGrid );

  // Prepare data
  locationOptions = [];
  {
    let locations = [
      "T.E.A.M. Center Housing Authority",
      "Williams Building",
      "Pizza Hut",
      "Simpson Park",
      "Powder Creak Park"
    ]; // This should be retrieved from the backend

    for(let location of locations)
      locationOptions[location] = location;
  }

  // Apply items to div grid
  basicRowItems(divGrid, formData, [
    {label: "Form ID"       , key: "formID"},
    {label: "Time Submitted", value: unixToHuman(formData.timeSubmitted)},
    {label: "Location"      , key: "location"     , type: "dropdown", apiFunction: 13, args: {formID: formData.formID}, options: locationOptions},
    {label: "Allergies"     , key: "allergies"    , type: "text"    , apiFunction: 12, args: {formID: formData.formID}},
    {label: "Lunches Needed", key: "lunchesNeeded", type: "text"    , apiFunction: 11, args: {formID: formData.formID}},
    {label: "Enabled"       , key: "isEnabled"    , type: "checkbox", apiFunction: 10, args: {formID: formData.formID}},
    {label: "Allow Photos"  , key: "allowPhotos"  , type: "checkbox", apiFunction: 24, args: {formID: formData.formID}},
    // {label: "Monday"        , key: "pickupMon"    , type: "checkbox", apiFunction:  9, args: {formID: formData.formID}},
    // {label: "Tuesday"       , key: "pickupTue"    , type: "checkbox", apiFunction:  9, args: {formID: formData.formID}},
    // {label: "Wed"           , key: "pickupWed"    , type: "checkbox", apiFunction:  9, args: {formID: formData.formID}},
    // {label: "Thursday"      , key: "pickupThu"    , type: "checkbox", apiFunction:  9, args: {formID: formData.formID}},
  ]);

  // Add date checkboxes
  for(let dateStr of ["Mon", "Tue", "Wed", "Thu"]) {
    divGrid.append(
      $("<label>").text("Pickup "+dateStr+":"),
      $("<input>", {type: "checkbox", style: "margin-right: auto;"})
        .prop("checked", (freshFormData["pickup"+dateStr] == 1)? true: false)
        .change(function() {
          updateServer($(this), 9, "setValue", { formID: formData.formID, dateStr: dateStr })
        })
    )
  }


  if("individuals" in freshFormData) { // Draw the individuals in a table
    let table = $("<table>");

    // Create the header for the table
    table.append($("<tr>").append(
      $("<th>").text("Name"),
      $("<th>").text("Actions"),
    ));

    for(let individual of freshFormData.individuals) {
      let row = $("<tr>");
      table.append(row);

      // console.log(individual);
      row.append($("<td>").text(individual.individualName));

      // Make action buttons
      let viewButton = $("<button>").text("Inspect").click(()=>{
        post("/ajax/admin.php", {
          function: 17,
          individualID: individual.individualID
        }, (obj)=>{inspectIndividual(obj.data);});
      });
      let removeButton = $("<button>")
        .text("Remove from Form")
        .click(async ()=>{
          let obj = await post("/ajax/admin.php", {
            function: 15,
            formID: formData.formID,
            individualID: individual.individualID
          });
          if(obj.code >= 100 && obj.code < 200) {
            console.log("Inspecting form with this data:", freshFormData);
            inspectForm(freshFormData);
            div.remove(); checkBlur();
          }
        })
      ;
      row.append($("<td>").append(
        viewButton, removeButton
      ));
    }
    div.append(table);
  }

  // Make buttons
  let button_addIndividual = $("<button>")
    .text("Add an Individual")
    .click(()=>searchIndividuals(async (individual)=>{
      // console.log(individual);
      await post("/ajax/admin.php", {
        function: 27,
        formID: formData.formID,
        individualID: individual.individualID
      });
      inspectForm(formData);
      div.remove(); checkBlur();
    }))
  ;
  let button_close = $("<button>")
    .text("Close")
    .click(async ()=>{
      // Close Window
      div.remove(); checkBlur();
    })
  ;

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append( button_addIndividual, button_close ));
}

function inspectOrganization(orgData) {
  // Create, add and check blur of notification object
  let div = $("<div>", {class: "notification induce-blur"});
  $("body").append(div); // Add the notification to the page
  checkBlur(); // Check if the screen should be blured

  // Create a grid to align the items for style
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})

  // Create title
  div.append( $("<h2>").text("Inspect Organizations"), divGrid );

  // Apply items to div grid
  basicRowItems(divGrid, orgData, [
    {label: "Organization ID", key: "orgID"},
    {label: "Name"           , key: "orgName"    , type: "text"    , apiFunction: 25, args: {orgID: orgData.orgID}},
    {label: "Main Contact"   , key: "mainContact", href: ()=>{inspectIndividual(orgData.mainContactID)}},
    {label: "Signup Contact" , key: "signupContact", href: ()=>{inspectIndividual(orgData.signupContactID)}},
  ]);

  // Make buttons
  let button_changeMainContact = $("<button>")
    .text("Change Main Contact")
    .click(async ()=>{
      searchIndividuals(async (result)=>{
        // TODO: Update our main contact
        await post("/ajax/admin.php", {
          function: 25,
          orgID: orgData.orgID,
          mainContact: result.individualID
        });
        inspectOrganization(orgData);
        div.remove(); checkBlur();
      })
    })
  ;
  let button_close = $("<button>")
    .text("OK")
    .click(()=>{
      // Close Window
      div.remove(); checkBlur();
    })
  ;

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append(button_changeMainContact, button_close) );
}


/**
 * Creates a notification object with a search input, when an individual is
 * selected the returnFunction will be called with the individual ID of the
 * selected user
 *
 * @param returnFunction The function that will be called when the individual is
 * selected.
 */
function searchIndividuals(returnFunction) {
  let searchTimeout;
  let div = $("<div>", {class: "notification induce-blur"});

  let searchInput = $("<input>", {type: "text", id: "inputBox", placeholder: "Individual's Name Here"});
  searchInput.on('input', function() {
    // Handle input
    clearTimeout(searchTimeout);

    // Get the input value
    const inputValue = searchInput.val();

    // Show loading message
    $('#loadingMessage').show();
    $("#results").empty();

    // Set a timeout to call the 'post' function after 1 second
    searchTimeout = setTimeout(async () => {
      try {
        let returnData = await post("/ajax/admin.php", {function: 26, searchTerm: inputValue});
        const resultArray = returnData.data;

        // Display the top 5 names
        const resultsList = $('#results');
        resultsList.empty();

        // Display the top 5 names
        resultArray.slice(0, 5).forEach((result) => {
          let text = result.individualID+": "+result.individualName;
          const listItem = $('<li>').text(text);
          // listItem.click(() => returnFunction(result));
          listItem.click(()=>{
            returnFunction(result);
            div.remove();
            checkBlur();
          });
          resultsList.append(listItem);
        });
      } catch (error) {
        console.error(error);
      } finally {
        // Hide loading message
        $('#loadingMessage').hide();
      }
    }, 1000);
  });
  div.append(
    searchInput,$("<br>"),
    $("<ui>", {id: "results"}),
    $("<p>", {id: "loadingMessage"}).text("Loading...")
  )
  div.append($("<button>").text("Cancel").click(()=>{
    div.remove();
    checkBlur();
  }));
  $("body").append(div);
}

function displayPhoneNumber(phoneNumber) {
  if(phoneNumber.length != 10) return phoneNumber;
  phoneNumberString = "("+phoneNumber.substring(0, 3)+") ";
  phoneNumberString += phoneNumber.substring(3,6)+"-";
  phoneNumberString += phoneNumber.substring(6);
  return phoneNumberString;
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