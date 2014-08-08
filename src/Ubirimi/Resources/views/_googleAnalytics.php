<?php
    use Ubirimi\Container\UbirimiContainer;
?>
<?php if (true === UbirimiContainer::get()['app.googleAnalytics']): ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-50263247-1', 'ubirimi.com');
        ga('require', 'displayfeatures');
        ga('send', 'pageview');

    </script>
<?php endif ?>