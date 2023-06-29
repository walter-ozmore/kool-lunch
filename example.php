<html>
  <head>
    <title>BattleBit XP/Score Calculator</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="stylesheet" href="/profile/global.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script>
      /**
       * Assist not kill 1, 2, 3, 4, 39, 44, 60, 62, 74, 42
       * Vehicle Hit 10, 40, 61
       */
      let data = {
        score: {
          Kill: {
            "Enemy Killed": 200,
            "Assist counts as kill": 200,
            "Headshot bonus": 400,
            "Defended/Attacked the Objective": 200,
            "Long Distance Kill": {
              200.7: 100,
              201.8: 101,
              250.0: 149,
              260.4: 180,
              237.8: 137,
              284.6: 184,
              335.3: 235,
              336.9: 236,
              377.9: 277,
              416.3: 316,
              432.4: 332,
              418.8: 318,
              900  : 800
            },
          },

          Medic: {
            "Friendly Revive": 400,
            "Friend Healed": 50,
            "Bleeding Stopped": 200,
            "Squadmate spawned on you": 200,
          },

          Other: {
            "Vehicle destroyed": 1300,
            "Flag Captured": 1000,
            "Squad Objective Followed": 800,
            "Neutralized Objective": 1000,
          }
        },

        level_xp: {
          39: 39000,
          49: 49000,
          51: 51000,
          77: 77000,
          78: 78000,
        }
      };

      function convert(input) {
        let returnArray = [];

        for (let [key, value] of Object.entries(input)) {
          returnArray.push( {x:key, y:value} );
        }

        return returnArray;
      }

      $(document).ready(function() {
        console.log( data );

        let args = {
          type: "scatter",
          data: {
            datasets: [{
              pointRadius: 5,
              pointBackgroundColor: "rgb(255,255,255)"
            }]
          },
          options: {
            legend: {display: false},
            title: {
              display: true,
              fontSize: 16,
              fontColor: "white"
            }
          }
        };
        let copy;

        // Draw Score vs. Distance
        copy = JSON.parse(JSON.stringify(args));
        copy.data.datasets[0].data = convert( data.score.Kill["Long Distance Kill"] );
        copy.options.title.text = "Score vs. Distance";
        new Chart("score_vs_distance", copy);

        // Draw Level vs. XP Required
        copy = JSON.parse(JSON.stringify(args));
        copy.data.datasets[0].data = convert( data["level_xp"] );
        copy.options.title.text = "Level vs. XP Required";
        new Chart("level_vs_xp", copy);


        // Draw calculator
        let calculator = $("#calculator");
        console.log( data.score );
        for (let section in data.score) {
          calculator.append( $("<h2>").text(section) );

          let group = $("<div>")
            .css("display", "grid")
            .css("grid-template-columns", "auto auto")
          ;
          for (let [key, value] of Object.entries(data.score[section])) {
            group
              .append( $("<p>").text(key) )
              .append( $("<input>").attr("type", "number") )
            ;
          }
          calculator.append( group );
        }

      });
    </script>
  </head>

  <body>
    <div class="content">
      <div class="title">
        <h2 id="title">Calculator</h2>
        <h3 id="title-sub" class="subtle"></h3>
      </div>

      <!-- style="display: grid; grid-template-columns: auto auto;" -->
      <div id="calculator" class="content-insert"></div>
    </div>
    <div class="content">
      <div class="title">
        <h2 id="title">Graphs</h2>
        <h3 id="title-sub" class="subtle"></h3>
      </div>

      <div id="content" class="content-insert">
        <canvas id="score_vs_distance" style="width:100%;"></canvas>
        <canvas id="level_vs_xp" style="width:100%;"></canvas>
      </div>
    </div>
  </body>
</html>