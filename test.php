<!DOCTYPE html>
<html>
  <head>
    <title>Test Cases | Kool Lunches</title>
    <meta name="robots" content="noindex">
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
    ?>

    <script>
      async function buttonClick() {
        let data = await post("/ajax/admin.php", {
          function: 16,
          formID: 246
        });

        console.log(data);
      }

      async function postTest() {
        let obj = JSON.parse($("#postTestJson").val());
        let data = await post("/ajax/admin.php", obj);
        console.log(data);
        jsonToHtml($("#output"), data);
      }

      function syntaxHighlight(json) {
        if (typeof json != 'string') {
            json = JSON.stringify(json, undefined, 2);
        }
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            var cls = 'number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'key';
                } else {
                    cls = 'string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'boolean';
            } else if (/null/.test(match)) {
                cls = 'null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
      }

      function jsonToHtml(jqueryObject, data, indent=0) {
        for(let key in data) {
          let value = data[key];

          let labelEle = $("<label>").text(key+": ");
          labelEle.css({color: "blue"});
          let valueEle = $("<label>");

          if (typeof value === 'object') {
            valueEle.text( typeof value );
            if(Array.isArray(value)) {
              valueEle.text( "Array" );
            }
          }

          if(typeof value == "number") {
            valueEle.text(value);
            valueEle.css({color: "green"});
          }

          if(typeof value == "string") {
            valueEle.text( "\""+value+"\"" );
            valueEle.css({color: "purple"});
          }
          jqueryObject.append(labelEle, valueEle, $("<br>"));
        }
      }
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div class="content">
      <p>User logged in as <span name="account-username"></span></p>
      <button onclick="buttonClick()">Test</button>
    </div>
    <div class="content">
      <h2>Manual Post</h2>
      <div>
        <label>Json</label>
        <textarea id="postTestJson" rows="4" cols="50"></textarea>
      </div>
      <button onclick="postTest()">Post</button>
      <p id="output"></p>
    </div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>
