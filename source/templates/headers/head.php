<!DOCTYPE html>
<html>    
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FlightsTracker - track your flights with ease</title>
        <link rel="stylesheet" href="/assets/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;600;700&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/ed3b1f2814.js" crossorigin="anonymous"></script>

        <!-- For menu on mobile devices -->
        <script>
            var navLinks = document.getElementById("navLinks");
            function showMenu(){
                navLinks.style.right = "0";
            }
            function hideMenu(){
                navLinks.style.right = "-200px";
            }
        </script>

        <!-- Rendering other needed scripts -->
        <?php if (!is_null($this->renderHeadScript)) $this->renderHeadScript->call($this); ?>
    
    </head>
    <body>