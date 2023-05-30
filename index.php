<!DOCTYPE html>
<html>
  <head>
    <title>Kool Lunches</title>

    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>

    <script src="/scripts/index.js"></script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div class="content">
      <iframe src="https://docs.google.com/presentation/d/e/2PACX-1vSxBW-X_1GKybMlrA-B4kD5QTEGf0UYux56FmcT3Ei7NEAMfisy6M9lkadUfErssLJBVUKpsElRjCFx/embed?start=true&loop=true&delayms=3000&amp;rm=minimal" frameborder="0" onload="resizeIframe(this)"></iframe>

      <p style="padding-top: 1em;">
        The Kool Lunches Program was started by the Fannin Community Foundation in 1999 and was coordinated by Ray and Ruth Havins who acted as co-chair Presidents of the program. The program began offering sack lunches that summer to students who were eligible for the free lunch program during the school year. Throughout their time of running the program, they served lunches to students in Bonham, Trenton, Leonard and Dodd City with the help of numerous volunteers. Unfortunately, the program was forced to end in 2007 due to lack of funding and loss of staff and necessary facilities to help feed the children of Fannin County.
      </p>

      <p style="padding-top: 1em;">
        After nine years without the Kool Lunches Program, with the blessing of Mr. and Mrs. Havins, a local coalition of advocates came together to start the program back up under the leadership of Megan Massey. Fannin County had seen an increasing number of students qualifying for free and reduced lunches during the school year and something needed to be done. After the comeback and continued support of our community, Megan passed the torch to Jodi Hunt and Brandy Stockton.
      </p>

      <p style="padding-top: 1em;">
        As it has been the goal of past presidents and board members, we are planning on being able to work alongside volunteer groups to offer our program to smaller towns and communities in Fannin County.
      </p>

      <p style="padding-top: 1em;">
        Not a lot has changed from the beginning. We still rely heavily on volunteers and donors and still see the need in our community to help those who may otherwise go hungry. We began the summer with 168 children signed up to receive lunches from our program. The entirety of Bonham ISD students qualify for free and reduced lunch during the past school year and our mission is to help families bridge the gap between school years. This summer, we served over 7000 lunches to our community and with your help, we can ensure that families can continue to benefit from our program.
      </p>
    </div>

    <div class="content">
      <h2 class="center-text" style="color: black">FAQ'S</h2>
      <div id="faq" class="faq"></div>

      <p class="center-text" style="margin-bottom: 0em;">
        All other questions can be sent through
      </p>
      <p class="link-row">
        <a href="https://www.facebook.com/koollunches/">Facebook</a>
        <a href="https://www.remind.com/join/koollunch5">Remind</a>
        <a href="">Email</a>
      </p>
    </div>

    <div class="content">
      <!-- <iframe src="https://docs.google.com/presentation/d/e/2PACX-1vQ15Qlu6CeWJkAIDFFkFgO2MIPIco7-KkOZWg3DJfRJSrrIpordmYhTj-ZnqBoKsDhYiC8ptKGL65NG/embed?start=true&loop=true&delayms=3000&amp;rm=minimal" frameborder="0" class="section"></iframe> -->

      <div>
        <h2 class="center-text" style="color: black">Monetary Donations</h2>
        <?php
          $query = "SELECT DISTINCT coll FROM Donation";
          $list = [];
          $result = $db_conn->query( $query );
          while ($row = $result->fetch_assoc()) {
            $list[] = $row["coll"];
          }


          for($x = 0;$x < sizeof($list);$x++) {
            $col = $list[$x];
            echo "<center><h3>" . ((strlen($col) > 0)? $col : "Others") . "</h3></center>";

            echo "<table style='margin: 0em auto;'>";
            $query = "SELECT * FROM Donation ".( (strlen($col) > 0)?"WHERE coll='$col' " : "WHERE coll IS NULL ") . "ORDER BY amount DESC";
            $result = $db_conn->query( $query );
            while ($row = $result->fetch_assoc()) {
              $name = $row["donatorName"];
              $amount = $row["amount"];
              echo "
              <tr>
                <td>$$amount</td>
                <td>$name</td>
              </tr>
              ";
            }
            echo "</table>";
          }
        ?>
      </div>
      <div class="center-text thank-you-grid">
        <div>
          <h2>Board Members</h2>
          <p>Jodi Hunt</p>
          <p>Brandy Stockton</p>
          <p>Kristy Agerlid</p>
          <p>Wendi Lindsey</p>
          <p>Steve Mohundro</p>
          <p>Phyllis Kinnaird</p>
          <p>Mary Karl</p>
          <p>Tillman Boyd</p>
        </div>

        <div>
          <h2>Volunteers</h2>
          <p>Church of Jesus Christ and Latter Day Saints</p>
          <p>Fannin County Sheriff's Office</p>

          <p>First Presbyterian Church</p>
          <p>Northside Church of Christ</p>
          <p>First Baptist Church</p>
          <p>Boyd Baptist Church</p>
          <p>Bethlehem Baptist Church</p>
          <p>First United Methodist Church</p>
          <p>7th and Main Baptist Church</p>

          <p>First United Methodist Church</p>
        </div>

        <div>
          <h2>Website</h2>
          <p>Walter Ozmore</p>
          <p>Rayna Fetters</p>
        </div>

        <div>
          <h2>Building</h2>
          <p>First Presbyterian Church-Bonham</p>
        </div>
      </div>
    </div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>
