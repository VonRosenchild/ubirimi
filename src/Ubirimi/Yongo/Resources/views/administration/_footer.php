<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

use Ubirimi\Container\UbirimiContainer;

?>
<div style="margin: 10px;" align="center">
    Project Management and Issue Tracker Software
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