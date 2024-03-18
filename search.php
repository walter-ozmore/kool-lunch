<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AutoComplete</title>
  <style>
    #results {
      list-style-type: none;
      padding: 0;
    }

    #results li {
      cursor: pointer;
      padding: 5px;
      border: 1px solid #ccc;
      margin: 2px;
    }

    #loadingMessage {
      display: none;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <?php
    require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
  ?>
</head>
<body>
  <div class="notification content">
    <input type="text" id="inputBox" oninput="handleInput()" placeholder="Individual's Name Here">
    <ul id="results"></ul>
    <div id="loadingMessage">Loading...</div>
  </div>

  <script>
    let searchTimeout;

    function handleInput() {
      clearTimeout(searchTimeout);

      // Get the input value
      const inputValue = $('#inputBox').val();

      // Show loading message
      $('#loadingMessage').show();
      $("#results").empty();

      // Set a timeout to call the 'post' function after 1 second
      searchTimeout = setTimeout(async () => {
        const url = 'YOUR_API_URL'; // Replace with your API URL
        const args = {function: 26, searchTerm: inputValue}; // Replace with your request parameters

        try {
          // Call the 'post' function
          console.log(args)
          let returnData = await post("/ajax/admin.php", args);
          const resultArray = returnData.data;
          console.log(resultArray)

          // Display the top 5 names
          displayResults(resultArray);
        } catch (error) {
          console.error(error);
        } finally {
          // Hide loading message
          $('#loadingMessage').hide();
        }
      }, 1000);
    }

    function displayResults(results) {
      const resultsList = $('#results');
      resultsList.empty();

      // Display the top 5 names
      results.slice(0, 5).forEach((result) => {
        console.log(result)
        const listItem = $('<li>').text(result.individualName);
        listItem.click(() => handleSelection(result));
        resultsList.append(listItem);
      });
    }

    function handleSelection(selectedItem) {
      // Fill in the text box with the selected name
      $('#inputBox').val(selectedItem.individualName);

      // Print the entire object to the console
      console.log(selectedItem);
    }
  </script>
</body>
</html>
