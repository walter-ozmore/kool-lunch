<div class="two-columns desktop" style="max-height: 12vh;">
    <div class="title-img">
        <img id="headerImage" src="/res/images/header.png">
    </div>
    <div>
        <div class="menu">
            <a class="lexend-body" href="/index">Home</a>

            <?php 
                $value = Database::getSetting("showSignUp")["value"];
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);

                if ($value) {
                    echo "<a class='lexend-body' href='/sign-up'>Signup</a>";
                }

                $value = Database::getSetting("showVolunteer")["value"];
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);

                if ($value) {
                    echo "<a class='lexend-body' href='/volunteer-sign-up'>Volunteer</a>";
                }
            ?>
            <a class="lexend-body" href="/about">About</a>
        </div>
    </div>
</div>

<div class="mobile">
    <div class="title-img">
        <img id="headerImage" src="/res/images/header.png">
    </div>
    <div>
        <div class="mobile-menu">
            <button class="lexend-body" onclick="dropdown()">Menu</button>
        </div>
    </div>
</div>

<!-- This is for login popup -->
<div id="blur-overlay"></div>