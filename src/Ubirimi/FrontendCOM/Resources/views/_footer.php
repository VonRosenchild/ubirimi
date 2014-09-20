<?php
    use Ubirimi\Container\UbirimiContainer;
?>
<div style="width: 100%; height: 2px; background: #ffffff url('/img/site/bg.page.png') 0 0 repeat-x scroll;"></div>
<div style="width: 100%; height: 2px; background: #ffffff url('/img/site/bg.page.png') 0 0 repeat-x scroll;"></div>
<div style="background-color: #205081">
    <br />
    <table align="center" cellspacing="0px" cellpadding="0px" class="footer">
        <tr>
            <td width="160px">
                <div class="link_footer_header">Products</div>
            </td>
            <td width="40px"></td>
            <td width="160px">
                <div class="link_footer_header">Company</div>
            </td>
            <td width="40px"></td>
            <td width="160px">
                <div class="link_footer_header">Resources</div>
            </td>
            <td width="40px"></td>
            <td width="160px">
                <div class="link_footer_header">Community</div>
            </td>
            <td width="40px"></td>
            <td width="160px">
                <div class="link_footer_header">Connect</div>
            </td>
        </tr>
        <tr>
            <td valign="top">
                <a itemprop="url" href="/product/yongo" style="color: #579ABD">Yongo</a>
                <br/>
                <a itemprop="url" href="/product/agile">Agile</a>
                <br/>
                <a itemprop="url" href="/product/helpdesk">Helpdesk</a>
                <br />
                <a itemprop="url" href="/product/svn-hosting">SVN Hosting</a>
                <br/>
                <a itemprop="url" href="/product/documentador">Documentador</a>
                <br/>
                <a itemprop="url" class="last" href="/product/events">Events</a>

            </td>
            <td></td>
            <td valign="top">
                <a itemprop="url" href="/blog">Blog</a>
                <br/>
                <a itemprop="url" href="/company">About us</a>
                <br/>
                <a itemprop="url" class="last" href="/contact">Contact</a>
                <br/>
                <a itemprop="url" href="/subscribe-newsletter">Subscribe to our newsletter</a>

            </td>
            <td></td>
            <td valign="top">
                <a target="_blank" href="https://support.ubirimi.net">Support</a>
            </td>
            <td></td>
            <td valign="top">
                <a href="https://www.facebook.com/Ubirimi/events" target="_blank">Events</a> <br/>
                <a target="_blank" href="https://groups.google.com/forum/#!forum/ubirimi">User groups</a>
            </td>
            <td></td>
            <td valign="top">
                <a itemprop="url" href="https://www.facebook.com/Ubirimi">Facebook</a>
                <br/>
                <a itemprop="url" href="https://twitter.com/ubirimi">Twitter</a>
                <br/>
                <a itemprop="url" href="https://plus.google.com/110668550600467817069/posts">Google+</a>
                <br/>
                <a itemprop="url" href="http://www.linkedin.com/company/ubirimi">Linkedin</a>
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <div style="font-size: 14px;">
                    Copyright &copy; 2012 - 2014 &bullet; <a itemprop="url" href="/privacy">Privacy</a> &bullet; <a itemprop="url" href="/terms-of-service">Terms
                        of Service</a> &bullet; Version <?php echo UbirimiContainer::get()['app.version'] ?>
                </div>
            </td>

            <td valign="top">
                <a itemprop="url" class="button_hp gray" style="color: #ffffff" href="https://www.ubirimi.com/sign-up">Free 30 days trial</a>
            </td>
        </tr>
    </table>


    <br/>

</div>
<script type="text/javascript" src="/js/vendor/jquery.cycle2.min.js"></script>

<script src="/js/plugins.js"></script>

<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>