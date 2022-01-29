<!------- Login box -------->
<section class="userform">
    <div class="userform-loginbox">
        <img src="/assets/images/avatar.png" class="avatar">
        <form method="post" action= <?php echo $this->generateUrl('login/tryLogin')?>>
            <?php 
                if (isset($_SESSION['user_loggedin']) && !$_SESSION['user_loggedin'])
                    echo '<h1>Invalid username or password.<br>Please try again.</h1>';
            ?>
            <p>Username</p>
            <input type="text" id="username" name="username" placeholder="Enter Username">
            <p>Password</p>
            <input type="password" id="password" name="password" placeholder="Enter Password">
            <input type="submit" name = "" value = "Log in"><br>
            <a href="<?php echo $this->generateUrl('registration/index'); ?>">Don't have an account?</a>
        </form>
    </div>
</section>