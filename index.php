<!DOCTYPE html>
<html>
  <head>
    <title>Kool Lunches</title>

    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      include realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";

      // Grabs and lists all monitary donations to the screen, if there is any
      // error skip
      function drawMonetaryDonations() {

        $drawString = "";
        $result = [];
        // $result = Database::getCollections();
        if ($result["code"] != 110) {return;}
        $drawString .= '<div style="margin-bottom: 1em"> <h2 class="center-text" style="color: black">Monetary Donations</h2>';
        $drawStringColData = "<center><h3>Unable to retrieve donation information at this time.</h3></center>";

        $data = $result["data"];
        foreach ($data as $col) {
          if ($col["coll"] != NULL) {
            $drawStringColData = "<center><h3>".$col["coll"]."</h3></center>";
          } else {
            $drawStringColData = "<center><h3>Others</h3></center>";
          }

          $drawString .= $drawStringColData;
          $drawString .=  "<table style='margin: 0em auto;'>";

          $result = Database::getCollectionDonations($col["coll"]);

          $data = $result["data"];

          foreach($data as $row) {
            $name = $row["donatorName"];
            $amount = $row["amount"];
            $drawString.=  "
            <tr>
              <td>$$amount</td>
              <td>$name</td>
            </tr>
            ";
          }
          $drawString.=  "</table>";
        }

        $drawString.=  '</div>';

        echo $drawString;
      }
    ?>

    <script>
      $(document).ready(function() {
        addFaq(
          "Where are the pick-up/drop-off locations?",
          `Simpson Park - <span class="subtle">Simpson Park is the park by I.W.Evans and L.H.Rather. You can meet us near the pavilion.</span><br><br>
          Powder Creek Park - <span class="subtle">Powder Creek Park is on South 5th St. in Bonham. You can meet us near the playground.</span><br><br>
          Pizza Hut - <span class="subtle">Here you can meet us near the back of the parking lot.</span><br><br>
          Housing Authority T.E.A.M Center building - <span class="subtle">Our lunches are dropped off at 806 W. 16th St. in Bonham. Here you should go into the building to pick up your lunches</span><br><br>`
        );

        addFaq(
          "I've signed up to receive Kool Lunches. What should I expect?",
          "Once we receive your form, your name is automatically added to the next serving day and you will be able to start picking up then. All lunches are free for children in our community."
        );

        addFaq(
          "What can be found in the sack lunch?",
          "Everyone who signs up will pick up a sack lunch consisting of a sandwich -peanut butter and jelly Tuesdays and Thursdays and meat (turkey or bologna) and cheese Mondays and Wednesdays-, chips, fruit cup, dessert and a juice. Sometimes, notes and other surprises can be found as well."
        );

        addFaq(
          "My child has food allergies? Can we still participate?",
          "Absolutely! Your lunches will be packed in a white bag with a label on the front with your child's name and their allergens listed. Your name will also be highlighted on the check off sheet and your drop off volunteer will be made aware of your situation. Brandy takes care of all of the allergy bags to make sure that everything is allergen free. The more specific you are when listing allergens, the better."
        );

        addFaq(
          "How many volunteers are needed each week?",
          "A minimum of 4 people that will be able to drive and deliver lunches. Between 8-12 people to help pack and double check lunches and make sandwiches."
        );

        addFaq(
          "I would like to volunteer with you but do not want to be involved in making sandwiches. Can I still help?",
          "We are always looking for volunteers to help with other things such as bagging cookies or decorating lunch sacks!"
        );

        // addFaq(
        //   "", // Question
        //   ""  // Answer
        // );
      });

      function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
      }
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div class="content">
      <iframe src="https://docs.google.com/presentation/d/e/2PACX-1vSxBW-X_1GKybMlrA-B4kD5QTEGf0UYux56FmcT3Ei7NEAMfisy6M9lkadUfErssLJBVUKpsElRjCFx/embed?start=true&loop=true&delayms=3000&amp;rm=minimal" frameborder="0" onload="resizeIframe(this)"></iframe>
      <center>
        <a href="/sign-up" class="button">SIGNUP</a>
        <a href="/volunteer-sign-up" class="button">VOLUNTEER</a>
      </center>
      <p>
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

      <?php @drawMonetaryDonations();?>
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
