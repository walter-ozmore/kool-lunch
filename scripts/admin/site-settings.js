$(document).ready(async function() {
  addPage("Settings", async (page)=>{
    // Load data for page
    page.append($("<center>").append(("<p>Loading Please Wait...</p>")))
    let obj = await post("/ajax/admin.php", {
			function: "getSettings"
		});
    let data = obj["data"];

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

  });
});