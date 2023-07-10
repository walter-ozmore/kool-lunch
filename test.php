<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1"></script>
<!-- Luxon, for time in graphs -->
<script src="https://cdn.jsdelivr.net/npm/luxon@2.0.1/build/global/luxon.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1.1.0/dist/chartjs-adapter-luxon.min.js"></script>

<script>
  function graph() {
    const DateTime = luxon.DateTime; // Reference the DateTime class from Luxon

    new Chart("nameofgraph", {
      type: 'scatter',
      data: {
        datasets: [{
          label: 'My Dataset',
          data: [
            { x: '2022-11-06', y: '20' },
            { x: '2022-11-07', y: '93' },
            { x: '2022-11-08', y: '23' }
          ],
          showLine: true,
          borderColor: 'rgb(100, 100, 255)'
        }],
      },
      options: {
        scales: {
          x: {
            type: 'time',
            time: {
              parser: function(value) {
                return DateTime.fromFormat(value, 'yyyy-MM-dd');
              },
              unit: 'day',
              displayFormats: {
                day: 'MMM d' // Modify the format to display only month and day
              }
            }
          }
        }
      }
    });
  }

  $(document).ready(graph);
</script>

<canvas id="nameofgraph"></canvas>
