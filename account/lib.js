class Account {
  static async login(username, password, sti) {
    let result = await post("/account/api.php", {
      funStr: "login",
      username: username,
      password: password,
      sti: sti
    });

    if(result.code == 0) location.reload();
  }
  static signup(username, email, password) {}
  static async logout() {
    await post("/account/api.php", {
      funStr: "logout"
    });
    location.reload();
  }
  static createWindow() {
    // Create notification element
    let not = $("<div>", {class: "notification"});
    $("body").append(not);

    // Create two pages, one for login one for signup so we can easly switch them
    let loginPage  = $("<div>");
    let signupPage = $("<div>").hide();
    not.append(loginPage, signupPage);

    // Create a grid to make things look nice for each page
    let loginGrid  = $("<div>", {
      class: "account-offset",
      style: "display: grid; grid-template-columns: 1fr 1.5fr;"
    });
    let signupGrid = loginGrid.clone();
    loginPage.append(loginGrid);
    signupPage.append(signupGrid);

    // Add grid elements
    let loginUsername = $("<input>", {type: "text"});
    let loginPassword = $("<input>", {type: "password"});
    loginGrid.append(
      $("<label>").text("Username"), loginUsername,
      $("<label>").text("Password"), loginPassword
    );

    let signupUsername = $("<input>", {type: "text"});

    // Add links to switch between signup and login
    let loginSignupLink = $("<a>").text("Create an Account").click(()=>{
      loginPage.hide(); signupPage.show();
    });
    let signupLoginLink = $("<a>").text("Login instead"    ).click(()=>{
      signupPage.hide(); loginPage.show();
    });
    loginGrid.append(loginSignupLink);
    signupGrid.append(signupLoginLink);

    // Add button to submit
    let signupButton = $("<button>").text("Sign Up").click(()=>{

    });
    let loginButton  = $("<button>").text("Login").click(()=>{
      let username = loginUsername.val();
      let password = loginPassword.val();

      Account.login(username, password, false);
    });
    loginPage .append($("<center>").append(loginButton))
    signupPage.append($("<center>").append(signupButton))
  }
}

/**
 * Logs the user in to the system then refreshes the page on
 * success
 */
function account_login() {
  // Grab info from document
  var $div = $("#account-window"); // Select account window

  // Select the input elements with the name attributes "username" and "password"
  var username = $div.find("[name='username']").val();
  var password = $div.find("[name='password']").val();
  let $error = $div.find("[name='error']");
  let sli = ($div.find("[name='sli']").val() == "true")? 1: 0;

  // Set up ajax
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState != 4 || this.status != 200) return;
    // let data = JSON.parse(this.responseText);
    let data;
    try {
      data = JSON.parse(this.responseText);
    } catch (error) {
      console.log(`Failed to parse JSON: ${this.responseText}`);
      return;
    }

    switch(data.code) {
      case 0: // Success
        // Refresh the page
        location.reload();
        break;
      case 1:
        if( $div.find("[name='password']") ) {
          $error.text("Invalid Login").show();
          break;
        }
      default:
        $error.text(data.message);
        $error.show();
    }
  };

  xhttp.open("POST", "/account/ajax/login", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  let args = `username=${username}&password=${password}&sli=${sli}`;
  xhttp.send(args);
}


function account_signup(linkCode = "") {
  // Check if info is valid

  // Grab info from document
  var $div = $("#account-window"); // Select account window
  var $submitButton = $div.find("[name='signup-button']");

  var email = $div.find("[name='email']").val();
  var username = $div.find("[name='username']").val();
  var password = $div.find("[name='password']").val();
  var repeat = $div.find("[name='repeat-password']").val();
  let $error = $div.find("[name='error']");

  // Make the request to the server
  $.post( "/account/ajax/signup.php",
    { email: email, username: username, password: password, repeat: repeat },
    function(result){
      let data;
      try {
        data = JSON.parse(result);
      } catch (error) {
        console.log(`Failed to parse JSON: ${result}`);
        return;
      }

      switch(data.code) {
        case 0: // Success
          // Refresh the page
          location.reload();
          break;
        default:
          $error.text(data.message).show();
      }
    }
  );
}


function account_checkSignup() {
  var $div = $("#account-window"); // Select account window
  var $submitButton = $div.find("[name='signup-button']");

  var email = $div.find("[name='email']").val();
  var username = $div.find("[name='username']").val();
  var password = $div.find("[name='password']").val();
  var repeat = $div.find("[name='repeat-password']").val();
  let $error = $div.find("[name='error']");

  let lengthRequirement = 7;

  // Check if the info is valid

  // Check email
  if(email.length <= 0) {
    $error.text("An email is required").show();
    return false;
  }

  // Check Password
  if(password.length < lengthRequirement) {
    $error.text("Your password must be atleast "+lengthRequirement+" characters long.").show();
    return false;
  }

  if(password != repeat) {
    $error.text("Passwords must match").show();
    return false;
  }

  $error.text("").hide();
  return true;
}


/**
 *
 * @returns The user object
 */
async function account_getUser(uid=null) {
  if(uid != null) {
    // Fetch the given user
    return null;
  }

  try {
    let str = await $.ajax({
      url: url = "/account/ajax/get-current-user.php",
      method: "POST"
    });

    let user;
    try {
      user = JSON.parse(str);
    } catch(e) {
      if(str.length == 0) str = "<empty string>";
      console.log(url+": " + str);
      return;
    }
    account_updateUser(user);

    return user;
  } catch (error) {
    // console.error("An error occurred during the AJAX request:", error);
    return null;
  }
}


/**
 * Logs out the current user then refreshes the page
 */
async function account_logout() {
  try {
    await $.ajax({
      url: "/account/ajax/logout.php",
      method: "POST"
    });

    // Refresh the page
    location.reload();
    return;
  } catch (error) {
    console.error("An error occurred during the AJAX request:", error);
    return false;
  }
}

function response(data) {
  if(data["code"] != 0) {
    // Some kind of error from the server
    // TODO: Handle the error
    return;
  }

  // Account window
  $("#account-window").remove()

  let user = data.user;
  account_updateUser(user);
}


/**
 * Create a login notification element that is standardized
 * across pages.
 *
 * Good luck to anyone who has to debug this nightmare
 */
async function account_window(args = {}) {
  console.log("Creating account window");
  function swap() {
    for(let ele of diff[diffValue]) {
      ele.hide();
    }

    diffValue = (diffValue == 1)? 0: 1;

    for(let ele of diff[diffValue]) {
      ele.show();
    }
  }

  let checkboxEle = $("<input>", {type: "checkbox", value: false, disable: true, name: "sli", style: "display: none;"});
  let checkbox = $("<p>").text("[ ]");
  let sli = $("<div>")
    .addClass( "account-hover" )
    .append(
      checkbox.css({display: "inline"}),
      checkboxEle,
      $("<p>").text(" Remember me").css({display: "inline"})
    )
    .click(function() {
        // checkbox.prop('checked', !checkbox.is(':checked'));
        if (checkbox.text() === "[x]") {
          checkbox.text("[ ]")
          checkboxEle.val(false);
          return
        }
        checkbox.text("[x]");
        checkboxEle.val(true);
    })
  ;

  let diffValue = 0;
  let diff = [
    [ // Unique to sign up
      $("<label>").text("Email"),
      $("<input>", {type: "text", name: "email"}),

      $("<label>").text("Repeat Password"),
      $("<input>", {type: "password", name: "repeat-password"}),

      $("<p>").text("Already have an account? Login here.").click(swap),
      $("<button>", {onclick: `account_signup(response, "${args.code}")`, name: "signup-button"}).text("Sign Up")
    ],
    [ // Unique to login
      sli,
      $("<p>").text("Forgot Password?"),
      $("<p>").text("Create an Account").click(swap),
      $("<button>", {onclick: "account_login()"}).text("Login")
    ]
  ];

  let div = $("<div>", {id: "account-window"})
    .addClass("account-window notification")
  ;

  // Login
  let offsetGrid = $("<div>") // Grid Div
    .addClass("account-offset")

    // Stuff in the grid div
    .append( diff[0][0], diff[0][1] )

    .append( $("<label>").text("Username") )
    .append( $("<input>", {type: "text", name: "username"}) )

    .append( $("<label>").text("Password") )
    .append( $("<input>", {type: "password", name: "password"}) )

    .append( diff[0][2], diff[0][3] )
  ;

  let grid = $("<div>") // Grid Div
    .addClass("account-grid")

    // Stuff in the grid div
    .append(
      $("<div>")
        .append(diff[1][0])
    )
    .append(
      $("<div>")
        .append(diff[1][1], diff[1][2], diff[0][4])
    )
  ;

  // Create the center element and the login button
  var centerElement = $("<center>");
  centerElement.append(
    $("<p>", {name: "error"}).hide().addClass("error"),
    diff[0][5], diff[1][3],
  );

  // Append the center element to the login div
  div.append(offsetGrid, grid, centerElement);
  $("body").append(div);
  swap();
}


/**
 * Updates display elements with the given user's information
 *
 * @param {array} user
 */
function account_updateUser(user) {
  $('[name="account-uid"]').text(user["uid"]);
  $('[name="account-username"]').text(user["username"]);
  $('[name="account-email"]').text(user["email"]);
}


/**
 * Check the logged in account
 */
async function checkAccount() {
  let response;
  try {
    response = await $.ajax({
      url: "/account/ajax/get-current-user.php",
      method: "POST"
    });

    if(response == false || response == null)
      return;
    let user = JSON.parse(response);
    account_updateUser(user);
  } catch (error) {
    // console.error("An error occurred during the AJAX request:", error);
    return;
  }
}

$(document).ready(async function() {
  checkAccount();
});