<?php
use Ubirimi\Container\UbirimiContainer;

?>
<div style="margin: 10px;" align="center">
    Customer Portal
    <span> &middot; </span>
    <a target="_blank" href="https://support.ubirimi.net/">Support</a>
    <span> &middot; </span>
    <a id="send_feedback" href="#">Send Feedback</a>
    <span> &middot; </span>
    Version: <?php echo UbirimiContainer::get()['app.version'] ?>
</div>
<div align="center">
    <img src="/img/site/bg.logo.png" style="margin-bottom: 18px; opacity: 0.5" />
</div>