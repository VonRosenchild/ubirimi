<div class="container page-container profile" style="width: 1000px; height: 500px;">

    <p class="leading">
        <?php

        require_once __DIR__ . '/_menu.php' ?>
    </p>

    <div>Hey <?php echo $session->get('user/first_name') . ' ' . $session->get('user/last_name') ?>, welcome to the Ubirimi customer portal.</div>

    <?php if ($installedFlag): ?>
        <div>Use the link below to access your products.<br />
            <a href="<?php echo $clientData['base_url'] ?>"><?php echo $clientData['base_url'] ?></a>
        </div>
    <?php else: ?>
        <h3>Your instance is being prepared for you. Give us a minute and we will let you know shortly. Thanks</h3>
    <?php endif ?>
</div>