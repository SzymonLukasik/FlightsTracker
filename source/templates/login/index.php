<!------- Login box -------->
<section class="login">
    <div class="loginbox">
        <img src="/assets/images/avatar.png" class="avatar">
        <form method="post" action= <?php echo $this->generateUrl('login/tryLogin')?>>
            <?php 
                if (isset($_SESSION['userloggedin']) && $_SESSION['userloggedin'])
                    echo '<h1>Invalid username or password.<br>Please try again.</h1>';
            ?>
            <p>Username</p>
            <input type="text" id="username" name="username" placeholder="Enter Username">
            <p>Password</p>
            <input type="password" id="password" name="password" placeholder="Enter Password">
            <input type="submit" name = "" value = "Login"><br>
        </form>
    </div>
</section>


<!------- Login box 
<section class="login">
    <div class="loginbox">
        <img src="images/avatar.png" class="avatar">
        <form action="login.php">
            <h1>Invalid username or password.<br>Please try again.</h1>
            <p>Username</p>
            <input type="text" name="" placeholder="Enter Username">
            <p>Password</p>
            <input type="password" name="" placeholder="Enter Password"><br>
            <input type="submit" name = "" value = "Login"><br>
        </form>
    </div>
</section>-------->
