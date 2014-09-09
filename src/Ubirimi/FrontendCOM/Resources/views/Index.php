<?php
    use Ubirimi\Util;
?>

<div class="home-products">
    <h1>Fast, most affordable productivity tools</h1>

    <div class="buttons_hp clearfix" style="margin-bottom: 20px">

        <?php if (Util::runsOnLocalhost()): ?>
            <?php $fastTryLink = "https://demo.ubirimi_net.lan" ?>
        <?php else: ?>
            <?php $fastTryLink = "https://demo.ubirimi.net" ?>
        <?php endif ?>
        <a class="button_hp gray large" style="margin-right: 20px;" href="<?php echo $fastTryLink ?>"> Fast Try<br> <span>no registration</span> </a>
        <a class="button_hp blue large" href="/sign-up"> Sign Up<br> <span>100% free</span> </a>

    </div>

    <h2>with a fixed price for the entire package</h2>

    <ul class="product-list clearfix">
        <li class="product yongo" itemscope itemtype="http://schema.org/IndividualProduct">
            <h3 itemprop="name">Yongo</h3>

            <p itemprop="description">Track and manage the issues, bugs, tasks, deadlines, code, hours.</p>
            <a itemprop="url" href="https://www.ubirimi.com/product/yongo" class="button_hp gray" data-slide="0">Learn more</a>
        </li>
        <li class="product agile" itemscope itemtype="http://schema.org/IndividualProduct">
            <h3 itemprop="name">Agile</h3>

            <p itemprop="description">Unlocking the power of Agile: planning, estimating and visualizing team activity.</p>
            <a itemprop="url" href="https://www.ubirimi.com/product/agile" class="button_hp gray" data-slide="1">Learn more</a>
        </li>
        <li class="product helpdesk" itemscope itemtype="http://schema.org/IndividualProduct">
            <h3 itemprop="name">Helpdesk</h3>

            <p itemprop="description">Powerful helpdesk solution for any organization. Keep in touch with your customers.</p>
            <a itemprop="url" href="https://www.ubirimi.com/product/helpdesk" class="button_hp gray" data-slide="1">Learn more</a>
        </li>

        <li class="product documentador" itemscope itemtype="http://schema.org/IndividualProduct" style="margin-top: 20px;">
            <h3 itemprop="name">Documentador</h3>

            <p itemprop="description">Content Creation, Collaboration & Knowledge Sharing software for teams.</p>
            <a itemprop="url" href="https://www.ubirimi.com/product/documentador" class="button_hp gray" data-slide="2">Learn more</a>
        </li>
        <li class="product svn" itemscope itemtype="http://schema.org/IndividualProduct" style="margin-top: 20px;">
            <h3 itemprop="name">Svn</h3>

            <p itemprop="description">Reliable, private hosting for your projects with unlimited users.</p>
            <a itemprop="url" href="https://www.ubirimi.com/product/svn-hosting" class="button_hp gray" data-slide="3">Learn more</a>
        </li>

        <li class="product events" itemscope itemtype="http://schema.org/IndividualProduct" style="margin-top: 20px;">
            <h3 itemprop="name">Events</h3>

            <p itemprop="description">Plan and keep track of people, projects and events. A complete calendar application.</p>
            <a itemprop="url" href="https://www.ubirimi.com/product/events" class="button_hp gray" data-slide="3">Learn more</a>
        </li>
    </ul>
</div>

<div align="center"><h2>fair pricing / free updates forever / free support</h2></div>
<hr size="1" />

<table align="center">
    <tr>
        <td>
            <div class="compare-container">
                <div align="center">
                    <h2>Compare with your current solution<sup style="font-size: 20px">*</sup></h2>
                </div>
                <div align="center">
                    <div class="compare-items">
                        <a href="#" class="yongo" data-slide="0"><span>Yongo</span></a>
                        <a href="#" class="agile" data-slide="1"><span>Agile</span></a>
                        <a href="#" class="documentador" data-slide="2"> <span>Documentador</span> </a>
                        <a href="#" class="svn" data-slide="3"> <span>SVN Hosting</span> </a>
                        <a href="#" class="events" data-slide="4"> <span>Events</span> </a>
                    </div>
                    <div class="marker-container">
                        <span class="marker"></span>
                    </div>

                    <div class="cycle-slideshow" data-cycle-fx="scrollHorz" data-cycle-timeout="0" data-cycle-slides="> div">
                        <div>
                            <?php require_once __DIR__ . '/_comparative_table_yongo.php' ?>
                        </div>
                        <div>
                            <?php require_once __DIR__ . '/_comparative_table_agile.php' ?>
                        </div>
                        <div>
                            <?php require_once __DIR__ . '/_comparative_table_documentador.php' ?>
                        </div>
                        <div>
                            <?php require_once __DIR__ . '/_comparative_table_svn.php' ?>
                        </div>
                        <div>
                            <?php require_once __DIR__ . '/_comparative_table_events.php' ?>
                        </div>
                    </div>
                </div>

                <div style="font-size:14px;"><br />* The comparison is made on the most basic plan (where applicable)</div>
            </div>

            <div align="center"><h2>Our company at your service</h2></div>

            <table align="center">
                <tr>
                    <td>
                        <div class="feature-list-container clearfix">
                            <ul class="feature-list align-left">
                                <li>
                                    <img src="/img/site/img.feature.check.png" alt="Quality is our first approach" title="Quality is our first approach">

                                    <h3>Quality is our first approach</h3>

                                    <p>We take testing our products seriously. Never deliver a new version without taking care everything runs smoothly.</p>
                                </li>
                                <li>
                                    <img src="/img/site/img.feature.lock.png" alt="Privacy - it matters" title="Privacy - it matters">

                                    <h3>Privacy - it matters</h3>

                                    <p>Your data is safe. Security certificates for network traffic and data integrity implemented inside each product.</p>
                                </li>
                                <li>
                                    <img src="/img/site/img.feature.flag.png" alt="Best price in town" title="Best price in town">

                                    <h3>Best deal</h3>

                                    <p>Our prices are the lowest. You cannot find a better deal out there.</p>
                                </li>
                                <li>
                                    <img src="/img/site/img.feature.light.png" alt="Light speed is not the limit" title="Light speed is not the limit">

                                    <h3>Light speed is not the limit</h3>

                                    <p>No matter how big your data is we make a commitment to deliver speed in every aspect of the products. Our on demand servers are fast.</p>
                                </li>
                            </ul>
                            <ul class="feature-list last align-left">
                                <li>
                                    <img src="/img/site/img.feature.hat.png" alt="Free. No extra or hidden fees" title="Free. No extra or hidden fees">

                                    <h3>Free for open source.</h3>

                                    <p>Our products are free for open source software.<br /><br /><br /></p>
                                </li>
                                <li>
                                    <img src="/img/site/img.feature.road.png" alt="Easy as walking and speaking" title="Easy as walking and speaking">

                                    <h3>Easy as walking</h3>

                                    <p>There is no need for training or any other documentation. Just start using it. This is how intuitive our products feel.</p>
                                </li>
                                <li>
                                    <img src="/img/site/img.feature.user.png" alt="You are our business" title="You are our business">

                                    <h3>You are our business</h3>

                                    <p>We take any requests or questions from you seriously. We deliver. Contact us at any time. We will answer.</p>
                                </li>
                                <li>
                                    <img src="/img/site/img.feature.grow.png" alt="We go beyond..." title="We go beyond...">

                                    <h3>We go beyond...</h3>

                                    <p>Our feature rich set is here to get even richer. We are day and night at work to make your experience better and our products richer.</p>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
