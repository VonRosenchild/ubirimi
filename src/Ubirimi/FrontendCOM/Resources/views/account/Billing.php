<div class="container page-container profile" style="width: 1000px">
    <p class="leading">
        <?php require_once __DIR__ . '/_menu.php' ?>
    </p>

    <?php if ($emptyCountry): ?>
        <div>It seems that your profile is incomplete. Please edit you <a href="/account/profile">profile</a> and fill in the country.</div>
        <div>Thanks</div>
    <?php else: ?>
        <?php if ($currentCardData): ?>
            <div>Your current payment method:</div>
            <div>Card number: xxxx xxxx xxxx <?php echo $currentCardData->getLastFour() ?></div>
            <div>Card holder: <?php echo $currentCardData->getCardHolder() ?></div>
            <div>Expires on: <?php echo $currentCardData->getExpireMonth() ?> / <?php echo $currentCardData->getExpireYear() ?></div>
        <?php else: ?>
            <div>Currently you do not have any payment methods set. </div>
            <div>Please set one by clicking the button bellow.</div>
        <?php endif ?>
        <br />
        <a itemprop="url" class="button_hp_small blue" href="/account/billing/update">Update Payment Method</a>
    <?php endif ?>
</div>
<br />