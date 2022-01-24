<!DOCTYPE html>
<html>    
    <body>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>FlightsTracker - track your flights with ease</title>
            <link rel="stylesheet" href="/assets/style.css">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;600;700&display=swap" rel="stylesheet"> 
            <script src="https://kit.fontawesome.com/ed3b1f2814.js" crossorigin="anonymous"></script>
        </head>
        
        <section class="sub-header">
            <nav>
                <a href="index.php"><img src="/assets/images/logo.png"></a>
                <div class="nav-links" id="navLinks">
                    <i class="fa fa-times" onclick="hideMenu()"></i>
                    <ul>
                        <li><a href="">Home</a></li>
                        <li><a href=<?php echo $this->generateUrl('flight/index'); ?>>Flight Data</a></li>
                        <li><a href="forum.php">Forum</a></li>
                        <li><a href="claim_tracker.php">ClaimTracker</a></li>
                        <li><a href="login.php">LOG IN</a></li>
                </div>
                <i class="fas fa-bars" onclick="showMenu()"></i>
            </nav>
            <h1>Flight Data</h1>
        </section>