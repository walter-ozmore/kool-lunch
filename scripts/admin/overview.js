// Add entry to selector
addPage("Overview", ()=>{
	// Clear the page
	let page = $("#page").empty();

	// Fetch data
	let data = fetchData({
		function: 1,
	});
});