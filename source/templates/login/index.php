<!------- Login box -------->
<section class="userform">
    <div class="userform-loginbox">
        <img src="/assets/images/avatar.png" class="avatar">
        <form method="post" action= <?php echo $this->generateUrl('login/tryLogin')?>>
        <!--TODO: czemu login_failed jest NULL...?-->
            <?php 
                debug('login_failed =' . \FlightsTracker\Controller\LoginController::$login_failed . "\n");
                if (\FlightsTracker\Controller\LoginController::$login_failed)
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