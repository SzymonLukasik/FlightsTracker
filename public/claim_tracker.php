<!DOCTYPE html>

<?php define('ROOT_PATH', dirname($_SERVER['DOCUMENT_ROOT'])); ?>

<html>
    <?php include_once(ROOT_PATH . '/source/templates/head.html'); ?>
    <body>
        <?php include_once(ROOT_PATH . '/source/templates/header.html'); ?>

        <!------- Claim Tracker content -------->
        <section class="claim-tracker">
            <div class="claim-tracker-col">
                <h1>We are the world's best website</h1>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                
                </p>
            </div>
            <div class="claim-tracker-col">
                <img src="/assets/images/doggy.jpg">
            </div>
        </section>

        <?php include_once(ROOT_PATH . '/source/templates/footer.html'); ?>
        <?php include_once(ROOT_PATH . '/source/templates/toggle_menu.html'); ?>

    </body>
</html>