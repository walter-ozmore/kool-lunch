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
 * @param {obj} individualData
 */
async function inspectIndividual(individualData) {
  // Check if this data need to be fetched
  if (typeof individualData === 'number' || typeof individualData === 'string') {
    let individualID = individualData;
    let data = await post("/ajax/admin.php", {
      function: 17,
      individualID: individualID
    });
    if(data.code < 100 || data.code > 200) return;
    individualData = data["data"];
  }

  let div = $("<div>", {class: "notification induce-blur"});
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
  div.append(
    $("<h2>").text("Inspect Individual"),
    divGrid,
  );

  // Apply to div grid
  divGrid.append(
    $("<label>").text("Individual ID:"),$("<p>").text(individualData.individualID),
    $("<label>").text("Individual Name:"),
    $("<input>", {type: "text", value: individualData.individualName})
      .change(function() {updateServer($(this), 24, "individualName", {individualID: individualData.individualID})})
  );

  // Show the individual's contact information
  divGrid.append(
    $("<label>").text("Prefered Contact:"),
    $("<input>", {type: "text", value: (individualData.preferredContact == null)? "None Specified": individualData.preferredContact})
      .change(function() {updateServer($(this), 24, "preferredContact", {individualID: individualData.individualID})})
  );
  divGrid.append(
    $("<label>").text("Phone Number:"),
    $("<input>", {type: "text", value: individualData.phoneNumber})
      .change(function() {updateServer($(this), 24, "phoneNumber", {individualID: individualData.individualID})})
  );
  divGrid.append(
    $("<label>").text("Email:"),
    $("<input>", {type: "text", value: individualData.email})
      .change(function() {updateServer($(this), 24, "email", {individualID: individualData.individualID})})
  );
  divGrid.append(
    $("<label>").text("Messenger:"),
    $("<input>", {type: "text", value: individualData.facebookMessenger})
      .change(function() {updateServer($(this), 24, "facebookMessenger", {individualID: individualData.individualID})})
  );

  // Create the delete button ahead of time and enabled it later
  let deleteButton = $("<button>")
    .text("Delete")
    .click(async ()=>{post("/ajax/admin.php", {
      function: 8,
      individualID: individualData.individualID
    }, (data)=>{
      if(data.code == 0) location.reload();
    })})
  ;

  // Add links for the user, like if they are atttached to a form or something
  post("/ajax/admin.php", {
    function: 7,
    individualID: individualData.individualID
  }, (json)=>{
    let data = json.data;
    let formIDEle = $("<p>");
    let volunteerFormIDEle = $("<p>");
    divGrid.append($("<label>").text("Signup Forms:"), formIDEle);
    divGrid.append($("<label>").text("Volunteer Forms:"), volunteerFormIDEle);

    // console.log("Data:", data)

    // Create links for each form that is clickable
    for(let form of data.Form) {
      if(form.formID == null) continue;

      formIDEle.append(
        $("<a>", {style: "display: inline; margin-right: .5em;", class: "clickable", href: ""})
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
    }inspectIn

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


async function inspectVolunteerForm(formData) {
  // Check if this data need to be fetched
  if (typeof formData === 'number' || typeof formData === 'string') {
    let uniqueID = formData;
    let data = await post("/ajax/admin.php", {
      function: 1,
      volunteerFormID: uniqueID
    });
    if(data.code < 100 || data.code > 200) return;
    formData = data["data"];
  }

  let div = $("<div>", {class: "notification induce-blur"});
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
  div.append(
    $("<h2>").text("Inspect Volunteer Form"),
    divGrid,
  );

  // Apply to div grid
  divGrid.append(
    $("<label>").text("Vounteer Form ID:"), $("<p>").text(formData.volunteerFormID),
    $("<label>").text("Individual Name:"), $("<p>").text(formData.individualName),
    $("<label>").text("Time Submitted:"), $("<p>").text(unixToHuman(formData.timeSubmitted)),
  );

  // Only show the contact methods that have data
  if(formData.phoneNumber != null) divGrid.append($("<label>").text("Phone Number:"), $("<p>").text(formData.phoneNumber));
  if(formData.email != null) divGrid.append($("<label>").text("Email:"), $("<p>").text(formData.email));
  if(formData.facebookMessenger != null) divGrid.append($("<label>").text("Messenger:"), $("<p>").text(formData.facebookMessenger));

  // Add checkbox stuff
  console.log(formData);
  let checkbox;
  checkbox = $("<input>", {type: "checkbox"});
  checkbox.change(function() { updateServer($(this), 18, "weekInTheSummer", {volunteerFormID: formData.volunteerFormID}); });
  if(formData.weekInTheSummer == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Week in the summer"), $("<br>"), );

  checkbox = $("<input>", {type: "checkbox"});
  checkbox.change(function() {updateServer($(this), 19, "bagDecoration", {volunteerFormID: formData.volunteerFormID}) });
  if(formData.bagDecoration == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Bag Decoration"), $("<br>"), );

  checkbox = $("<input>", {type: "checkbox"});
  checkbox.change(function() { updateServer($(this), 20, "fundraising", {volunteerFormID: formData.volunteerFormID}) });
  if(formData.fundraising == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Fundraising"), $("<br>"), );

  checkbox = $("<input>", {type: "checkbox"});
  checkbox.change(function() { updateServer($(this), 21, "supplyGathering", {volunteerFormID: formData.volunteerFormID}) });
  if(formData.supplyGathering == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Supply Gathering"), $("<br>"), );

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append(
    $("<button>")
      .text("Select Individual")
      .click(()=>searchIndividuals(async (individual)=>{
        await post("/ajax/admin.php", {
          function: 28,
          volunteerFormID: formData.volunteerFormID,
          individualID: individual.individualID
        });
        inspectForm(formData);
        div.remove(); checkBlur();
      })),
    $("<button>")
      .text("Delete")
      .click(async ()=>{
        post("/ajax/admin.php", {
          function: 6,
          formID: formData.volunteerFormID
        }, (json)=>{
          if(json.code == 110) {
            window.location.href = window.location.origin + "/admin?page=Volunteer Forms";
          }
        });
      }),
    $("<button>")
      .text("View Individual")
      .click(()=>{
        console.log(formData);
        post("/ajax/admin.php", {
          function: 17,
          individualID: formData.individualID
        }, (obj)=>{inspectIndividual(obj.data);});
      }),
    $("<button>")
      .text("Close")
      .click(async ()=>{ div.remove(); checkBlur(); }),
  ));
  $("body").append(div);
  checkBlur();
}


async function inspectForm(formData) {
  // Fetch some fresh data to work with
  let freshFormData = (await post("/ajax/admin.php", {
    function: 3,
    formID: formData.formID
  })).data;

  if(freshFormData == null) {
    displayError("Missing fresh form data");
    return;
  }

  // Display data for testing purposes
  // console.log(freshFormData);

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
  );

  {// Add location dropdown
    let locationDropdown = $("<select>");
    let locations = [
      "T.E.A.M. Center Housing Authority",
      "Williams Building",
      "Pizza Hut",
      "Simpson Park",
      "Powder Creak Park"
    ]; // This should be retrieved from the backend

    for(let location of locations) {
      let option = $("<option>", {value: location}).text(location);
      locationDropdown.append(option);
    }
    locationDropdown.val( formData.location );
    locationDropdown.change(function() {updateServer($(this), 13, "location", {formID: formData.formID})});


    divGrid.append(
      $("<label>").text("Location:"),
      locationDropdown,
    );
  }

  // Add allergies
  divGrid.append(
    $("<label>").text("Allergies:"),
    $("<input>", {type: "text", value: formData.allergies})
      .change(function() {updateServer($(this), 12, "allergies", {formID: formData.formID})})
    ,
  );

  // Add lunches need input
  divGrid.append(
    $("<label>").text("lunchesNeeded:"),
    $("<input>", {type: "number", value: formData.lunchesNeeded})
      .change(function() {updateServer($(this), 11, "numLunches", {formID: formData.formID})}),
  );

  // Add enabled checkbox
  divGrid.append(
    $("<label>").text("Enabled:"),
    $("<input>", {type: "checkbox", style: "margin-right: auto;"})
      .prop("checked", (freshFormData.isEnabled == 1)? true: false)
      .change(function() {updateServer($(this), 10, "isEnabled", {formID: formData.formID})})
  )

  {
    let checkbox;
    checkbox = $("<input>", {type: "checkbox", style: "margin-right: auto;"});
    checkbox.change(function() { updateServer($(this), 24, "allowPhotos", {formID: formData.formID}); });
    if(formData.allowPhotos == "1") checkbox.prop('checked', true);
    divGrid.append( $("<label>").text("Allow Photos:"), checkbox );
  }

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


  { // Draw the individuals in a table
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
            inspectForm(formData);
            div.remove();
            checkBlur();
          }
        })
      ;
      row.append($("<td>").append(
        viewButton, removeButton
      ));
    }
    div.append(table);
  }

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append(
    $("<button>")
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
      })),
    $("<button>")
      .text("Close")
      .click(async ()=>{ div.remove(); checkBlur(); }),
  ));
  $("body").append(div);
  checkBlur();
}

function inspectOrganization(orgData) {
  let div = $("<div>", {class: "notification induce-blur"});
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
  div.append(
    $("<h2>").text("Inspect Organizations"),
    divGrid,
  );

  divGrid.append(
    $("<label>").text("Name:"),
    $("<input>", {type: "text", value: orgData.orgName})
      .change(function() {updateServer($(this), 25, "orgName", {orgID: orgData.orgID})}),
  );

  divGrid.append(
    $("<label>").text("Main Contact:"),
    $("<p>").text(orgData.mainContact)
  );

  divGrid.append(
    $("<label>").text("Signup Contact:"),
    $("<p>").text(orgData.signupContact)
  );

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append(
    $("<button>")
      .text("Change Main Contact")
      .click(async ()=>{
        searchIndividuals(async (result)=>{
          console.log(result);
          // TODO: Update our main contact
          await post("/ajax/admin.php", {
            function: 25,
            orgID: orgData.orgID,
            mainContact: result.individualID
          });
          inspectOrganization(orgData);
          div.remove(); checkBlur();
        })
      }),
    $("<button>")
      .text("OK")
      .click(async ()=>{ div.remove(); checkBlur(); }),
  ));
  $("body").append(div);
  checkBlur();
}

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