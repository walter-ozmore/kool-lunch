<div class="two-columns" style="max-height:12vh;">
    <div class="title-img">
        <img id="headerImage" src="/res/images/header.png">
    </div>
    <div>
        <div class="menu">
            <a class="lexend-body" href="/redesign/index">Home</a>

            <?php 
                $value = Database::getSetting("showSignUp")["value"];
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);

                if ($value) {
                    echo "<a class='lexend-body' href='/redesign/sign-up'>Signup</a>";
                }

                $value = Database::getSetting("showVolunteer")["value"];
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);

                if ($value) {
                    echo "<a class='lexend-body' href='/redesign/volunteer-sign-up'>Volunteer</a>";
                }
            ?>
            <a class="lexend-body" href="/redesign/about">About</a>
        </div>
    </div>
</div>

<!-- This is for login popup -->
<div id="blur-overlay"></div>