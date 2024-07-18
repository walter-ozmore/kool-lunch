$(document).ready(async function() {
  addPage("Settings", async (page)=>{
    // Load data for page
    page.append($("<center>").append(("<p>Loading Please Wait...</p>")))
    let obj = await post("/ajax/admin.php", {
			function: "getSettings"
		});
    let data = obj["data"];
    console.log(data);

    /**
     * Future suggestions
     *  - Mentions Markdown
     *  - Show donations on home page
     *  - Signup warning message
     *  - Volunteer options
     */

    // Build our page
    page.empty().append(
      $("<input>", {type: "checkbox", checked: data["showSignUp"]["value"]})
        .change(function() {updateServer($(this), "setSetting", "value", { key: "showSignUp", type: "markdown"}) }),
      $("<label>").text("Show signup button on home page:"),
      $("<br>"),

      $("<input>", {type: "checkbox", checked: data["showVolunteer"]["value"]})
        .change(function() {updateServer($(this), "setSetting", "value", { key: "showVolunteer", type: "markdown"}) }),
      $("<label>").text("Show volunteer button on home page:"),
      $("<br>"),


      $("<div>", {style: "margin: .5em auto 0em auto; max-width: 80em;"}).append(
        $("<h3>", {style: "margin: .5em auto 0em auto; max-width: 20em;"}).text("Home Page Markdown"),
        $("<textarea>")
          .css({width: "100%", height: "15em"})
          .text(data["homePageText"]["value"])
          .change(function() {updateServer($(this), "setSetting", "value", { key: "homePageText", type: "markdown"}) }),

        $("<h3>", {style: "margin: .5em auto 0em auto; max-width: 20em;"}).text("FAQ Markdown"),
        $("<textarea>")
          .css({width: "100%", height: "15em"})
          .text(data["faqText"]["value"])
          .change(function() {updateServer($(this), "setSetting", "value", { key: "faqText", type: "markdown"}) })
      ),
      $("<center>").append(
        $("<button>", {disabled: true}).text("Edit Mentions"),
        $("<br>"),
        $('<a href="https://markdownlivepreview.com/" target="_blank">Online Markdown Viewer</a>')
      )
    );


    // Add email settings
    let table = $("<table>").append(
      $("<tr>").append(
        $("<th>").text("User"),
        $("<th>").text("Email"),
        $("<th>").text("Sign Up"),
        $("<th>").text("Volunteer Sign Up"),
      )
    );
    page.append(
      $("<h2>", {style: "margin-top: 2.5em; text-align: left;"}).text("Emails"),
      $("<p>").text("This is a test"),
      table
    );

    for(let uid in data["users"]) {
      let user = data["users"][uid];
      console.log(user);

      let row = $("<tr>");
      table.append(row.append(
        $("<td>").text( user.username ),
        $("<td>").text( user.email ),
      ));

      let checkbox;
      checkbox = $('<input type="checkbox">');
      checkbox.change(function() {
        // Sending update to server
        updateServer(
          $(this), // Passing the element though
          "setUserSetting", // API Function Name
          "value", // Name of the post argument with the data
          { uid: uid, dataKey: "emailSignup" } // Other data needed with post
        )
      });
      if(user.emailSignup == 1) checkbox.prop('checked', true);
      row.append( $("<td>").append(checkbox) );

      checkbox = $('<input type="checkbox">');
      checkbox.change(function() {
        // Sending update to server
        updateServer(
          $(this), // Passing the element though
          "setUserSetting", // API Function Name
          "value", // Name of the post argument with the data
          { uid: uid, dataKey: "emailVolSignup" } // Other data needed with post
        )
      });
      if(user.emailVolSignup == 1) checkbox.prop('checked', true);
      row.append( $("<td>").append(checkbox) );
    }
  });
});