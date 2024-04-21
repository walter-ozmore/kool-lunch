$(document).ready(async function() {
	addPage("Settings", (page)=>{
		page.append( $("<input>", {type: "checkbox", disabled: true}), $("<label>").text("Show donations on home page:"), $("<br>"));
    page.append( $("<input>", {type: "checkbox", disabled: true}), $("<label>").text("Show signup button on home page:"), $("<br>"));
    page.append( $("<input>", {type: "checkbox", disabled: true}), $("<label>").text("Show volunteer button on home page:"), $("<br>"));

    page.append(
      $("<div>", {style: "margin: .5em auto 0em auto; max-width: 80em;"}).append(
        $("<h3>", {style: "margin: .5em auto 0em auto; max-width: 20em;"}).text("Home Page Markdown"),
        $("<textarea>").css({
          width: "100%",
          height: "15em"
        }).text("Nostrud sunt sunt ullamco Lorem laboris et. Ipsum tempor ea nisi proident Lorem qui esse voluptate commodo elit id eiusmod tempor. Et enim ad amet ipsum eiusmod id est. Officia sit reprehenderit mollit Lorem sunt minim ipsum adipisicing nulla aliquip incididunt eiusmod excepteur.")
      )
    );

    page.append(
      $("<div>", {style: "margin: .5em auto 0em auto; max-width: 80em;"}).append(
        $("<h3>", {style: "margin: .5em auto 0em auto; max-width: 20em;"}).text("FAQ Markdown"),
        $("<textarea>").css({
          width: "100%",
          height: "15em"
        }).text("Enim ex elit ad minim cillum nisi laborum nulla. Non incididunt exercitation elit ut dolore aliquip mollit do in exercitation. Nulla ad est occaecat tempor aliqua.")
      )
    );

    page.append(
      $("<center>").append(
        $("<button>", {disabled: true}).text("Edit Mentions")
      ));
	});
});