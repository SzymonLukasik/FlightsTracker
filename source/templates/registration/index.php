<!------- Registration box -------->
<section class="userform">
    <div class="userform-registrationbox">
        <img src="/assets/images/avatar.png" class="avatar">
        <form method="post" action= <?php echo $this->generateUrl('registration/tryRegister')?>>
            <?php 
                if (isset($_SESSION['registration_failed']) && $_SESSION['registration_failed'])
                    echo '<h1>Incorrect credentials</h1>';
            ?>
            <p>Username</p>
            <input type="text" id="username" name="username" placeholder="Enter Username">
            <p>Password</p>
            <input type="password" id="password" name="password" placeholder="Enter Password">
            <p>First name</p>
            <input type="text" id="first_name" name="first_name" placeholder="Enter First Name">
            <p>Surname</p>
            <input type="text" id="surname" name="surname" placeholder="Enter Surname">
            <p>Birthdate</p>
            <input type="date" id="birthdate" name="birthdate">
            <input type="submit" name = "" value = "Register"><br>
        </form>
    </div>
</section>