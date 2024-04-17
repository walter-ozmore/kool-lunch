$(document).ready(async function() {
	addPage("Settings", (page)=>{
		page.append( $("<label>").text("Show donations on home page:"), $("<input>", {type: "checkbox", disabled: true}), $("<br>"));
    page.append( $("<label>").text("Show signup button on home page:"), $("<input>", {type: "checkbox", disabled: true}), $("<br>"));
    page.append( $("<label>").text("Show volunteer button on home page:"), $("<input>", {type: "checkbox", disabled: true}), $("<br>"));
    page.append( $("<button>").text("Edit FAQ"), $("<br>"));
    page.append( $("<button>").text("Edit Mentions"), $("<br>"));
    page.append( $("<button>").text("Edit Homepage text"), $("<br>"));
	});
});