<nav>
        <a href="<?php echo $this->generateUrl('homepage'); ?>"><img src="/assets/images/logo.png"></a>
        <div class="nav-links" id="navLinks">
            <i class="fa fa-times" onclick="hideMenu()"></i>
            <ul>
                <li><a href="<?php echo $this->generateUrl('homepage'); ?>">Home</a></li>
                <li><a href="<?php echo $this->generateUrl('flight/index'); ?>">Flight Data</a></li>
                <li><a href="forum.php">Forum</a></li>
                <li><a href="claim_tracker.php">ClaimTracker</a></li>
                <li><a href="login.php">LOG IN</a></li>
            </ul>
        </div>
        <i class="fas fa-bars" onclick="showMenu()"></i>
</nav>