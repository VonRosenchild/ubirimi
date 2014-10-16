<?php use Ubirimi\Container\UbirimiContainer;

if ($comments): ?>

    <?php if ($childPages): ?>
        <br />
    <?php endif ?>

    <div class="headerPageText" style="border-bottom: 1px solid #DDDDDD;"><?php echo count($comments) . ' Comment' . $pluralCommentsHTML ?></div>
    <div style="float: left; display: block; width: 100%">
        <?php echo UbirimiContainer::get()['repository']->get('documentador.entity.comment')->getCommentsLayoutHTML($comments, $htmlLayout, null, 0); ?>
    </div>
<?php endif ?>