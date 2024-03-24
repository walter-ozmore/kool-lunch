# Unassigned
 - [ ] Monetary Donations -> Unsure if should show amount, or at all. Ask Brandy next week
 - [ ] Merge function -> Given individualID old and new, replace all individualID-old links with individualID-new (next year)
 - [ ] Fix new individuals not have a prefered method of contact
 - [ ] Inspecting a form with multiple individuals attached shows no individuals in the data
 - [ ] Inspecting volunteer form 53 then inspecting the individual on the form causes the error
       "Fatal error: Uncaught mysqli_sql_exception: Subquery returns more than 1 row in /var/www/kool-lunch/res/lib.php:919 Stack trace: #0 /var/www/kool-lunch/res/lib.php(919): mysqli->query() #1 /var/www/kool-lunch/ajax/admin.php(79): Database::getAllLinks() #2 {main} thrown in /var/www/kool-lunch/res/lib.php on line 919"
 - [ ] Search individual UI need elements searched
 - [ ] No current way for a user to delete orgs
 - [ ] No current way for a user to delete a form
 - [ ] Typing in a text box then closing the notification object without clicking anywhere else first will not send the update request to the server to update the given element

# Walter's
 - [ ] Fix admin menu not being able to change the remind status of an individual
 - [ ] Fix admin menu not refreshing or updating when an individual is deleted
 - [ ] Following signup form link from individuals only refreshes the page
       instead of bringing up the sign up form's inspect
 - [ ] Changing the main contact on in inspect org does not reload the org or the table

# Rayna's
 - [ ] Fix new individuals not having remind status set in the database