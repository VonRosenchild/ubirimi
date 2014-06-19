<?php use Ubirimi\Util; ?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
    <?php require_once __DIR__ . '/_header.php' ?>

    <body>
        <header style="background-color: #ffffff">
            <div align="center">
                <?php if (Util::runsOnLocalhost()): ?>
                    <a href="http://ubirimi.lan"><img src="/img/site/bg.logo.png" /></a>
                <?php else: ?>
                    <a href="https://www.ubirimi.com"><img src="/img/site/bg.logo.png" /></a>
                <?php endif ?>
            </div>
        </header>

        <div>
            <?php require_once __DIR__ . '/' . $content ?>
        </div>

        <div>
            <?php require_once __DIR__ . '/_footer.php' ?>
        </div>
    </body>
</html>
