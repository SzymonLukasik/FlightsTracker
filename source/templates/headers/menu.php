<nav>
        <a href="<?php echo $this->generateUrl('homepage/index'); ?>"><img src="/assets/images/logo.png"></a>
        <div class="nav-links" id="navLinks">
            <i class="fa fa-times" onclick="hideMenu()"></i>
            <ul>
                <li><a href="<?php echo $this->generateUrl('homepage/index'); ?>">Home</a></li>
                <li><a href="<?php echo $this->generateUrl('flight/index'); ?>">Flight Data</a></li>
                <li><a href="<?php echo $this->generateUrl('forum/index'); ?>">Forum</a></li> 

                <li><a href="<?php echo $this->generateUrl('tracker/index'); ?>">ClaimTracker</a></li>
                
                <li><?php 
                    if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'])
                        echo('<a href="' . $this->generateUrl('login/logout') . '">LOG OUT</a></li>');
                    else
                        echo('<a href="' . $this->generateUrl('login/index') . '">LOG IN</a></li>');
                ?>
                <li><?php 
                    if (!isset($_SESSION['logged_user']) || !$_SESSION['logged_user'])
                        echo('<a href="' . $this->generateUrl('registration/index') . '">REGISTER</a></li>');
                ?>
                <li><?php 
                    if (isset($_SESSION['logged_user']) && $_SESSION['logged_user'])
                        echo('<a href="' . $this->generateUrl('account/show', ['username' => $_SESSION['logged_user']]) . '">ACCOUNT</a></li>');
                ?>
            </ul>
        </div>
        <i class="fas fa-bars" onclick="showMenu()"></i>
</nav>