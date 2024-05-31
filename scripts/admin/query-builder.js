$(document).ready(async function() {
	addPage("Query Builder", (page)=>{
    sourceOptions = ["Signups", "Volunteer Forms", "Organizations", "Individuals"]

    sourceOptionElement = $("<select>");

    for(source of sourceOptions) {
      sourceOptionElement.append( $("<option>", {value: source}).text(source) );
    }

		page.append($("<label>").text("Source"), sourceOptionElement);
	});
});