onWindowLoad( function() {
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