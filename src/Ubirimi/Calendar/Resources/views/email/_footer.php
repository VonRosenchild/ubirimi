<?php
use Ubirimi\Container\UbirimiContainer;

?>
        <br />
        <div>
            <img src="https://www.ubirimi.com/img/bg.page.png" />
            <br />
            <span style="color: #808080; font-size: 12px;">This message was sent by Ubirimi <?php echo UbirimiContainer::get()['app.version'] ?></span>
        </div>
    </div>
</table>