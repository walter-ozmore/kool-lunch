$(document).ready(async function() {
	addPage("Settings", async (page)=>{
    let homePageText = $("<textarea>")
      .css({width: "100%", height: "15em"})
      .text("Loading...")
      .prop('disabled', true)
      .change(function() {
        updateServer($(this), "setSetting", "value", { key: "homePageText", type: "markdown" })
      })
    ;

    let faqText = $("<textarea>")
      .css({width: "100%", height: "15em"})
      .text("Loading...")
      .prop('disabled', true)
      .change(function() {
        updateServer($(this), "setSetting", "value", { key: "faqText", type: "markdown" })
      })
    ;

		page.append( $("<input>", {type: "checkbox", disabled: true}), $("<label>").text("Show donations on home page:"), $("<br>"));
    page.append( $("<input>", {type: "checkbox", disabled: true}), $("<label>").text("Show signup button on home page:"), $("<br>"));
    page.append( $("<input>", {type: "checkbox", disabled: true}), $("<label>").text("Show volunteer button on home page:"), $("<br>"));

    page.append(
      $("<div>", {style: "margin: .5em auto 0em auto; max-width: 80em;"}).append(
        $("<h3>", {style: "margin: .5em auto 0em auto; max-width: 20em;"}).text("Home Page Markdown"),
        homePageText
      )
    );

    page.append(
      $("<div>", {style: "margin: .5em auto 0em auto; max-width: 80em;"}).append(
        $("<h3>", {style: "margin: .5em auto 0em auto; max-width: 20em;"}).text("FAQ Markdown"),
        faqText
      )
    );

    page.append(
      $("<center>").append(
        $("<button>", {disabled: true}).text("Edit Mentions"),
        $("<br>"),
        $('<a href="https://markdownlivepreview.com/" target="_blank">Online Markdown Viewer</a>')
      ));

    // Load data for page
    post("/ajax/admin.php", {
			function: "getSetting",
      key: "homePageText"
		}, (obj)=>{
      homePageText
        .text(obj["data"])
        .prop('disabled', false);
      ;
    });

    post("/ajax/admin.php", {
			function: "getSetting",
      key: "faqText"
		}, (obj)=>{
      faqText
        .text(obj["data"])
        .prop('disabled', false);
      ;
    });
	});
});