<?php use Ubirimi\Container\UbirimiContainer; ?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <?php if ($page == null): ?>
        <title>Project management and collaboration tools | Ubirimi</title>
        <meta name="description" content="Fast, most affordable productivity tools. Customers all over the world choose Ubirimi every day to improve their enterprise process lifecycle, project management, collaboration and product quality." />
    <?php elseif ($page == 'yongo'): ?>
        <title>The project tracker for teams planning, building and launching great products | Yongo Ubirimi</title>
        <meta name="keywords" content="yongo, project management software, bug tracking, issue tracking, project tracking software, issue tracker, bug management, task management, milestones, time tracking"/>
        <meta name="description" content="The project tracker for teams planning, building and launching great products. Use Yongo to capture and organize issues, work through action items, and stay up to date with team activity."/>
    <?php elseif ($page == 'cheetah'): ?>
        <title>Agile Project Management Software | Cheetah Ubirimi</title>
        <meta name="keywords" content="scrum, agile development, agile software, agile project management, agile software development, agile planning, scrum teams, agile board, agile tools"/>
        <meta name="description" content="Cheetah unlocks the power of Agile, whether you are a seasoned Agile expert or just getting started. Creating and estimating stories, building a sprint backlog, identifying team commitment and velocity, visualizing team activity and reporting team progress has never been so easy."/>
    <?php elseif ($page == 'documentador'): ?>
        <title>Team Collaboration Software | Documentador Ubirimi</title>
        <meta name="keywords" content="team collaboration, collaboration software, content collaboration, collaboration, documentation software, enterprise wiki software, knowledge management software"/>
        <meta name="description" content="Documentador connects teams with the content, knowledge, and co-workers they need to get work done, faster. Crowd-source meeting notes, share files, define product requirements, and make decisions – all in one place."/>
    <?php elseif ($page == 'svn'): ?>
        <title>Reliable, private hosting for your projects with unlimited users | SVN Ubirimi</title>
        <meta name="keywords" content="free svn hosting, svn provider, svn solution, cloud, reliable"/>
        <meta name="description" content="You enjoy 10 GB of space on our dedicated servers while we take care of the entire setup and maintenance procedures. Our SVN Hosting comes out of the box with unlimited number of SVN users. We do it right."/>
    <?php elseif ($page == 'pricing'): ?>
        <title>Pricing | Ubirimi</title>
    <?php elseif ($page == 'blog'): ?>
        <title>Blog | Ubirimi</title>
    <?php elseif ($page == 'company'): ?>
        <title>Company | Ubirimi</title>
        <meta name="description" content="We are a startup focused on delivering a versatile project management product suite together with a very competitive market strategy. Our focus is on simplicity, ease of use, fast product maturity and customer support."/>
    <?php elseif ($page == 'contact'): ?>
        <title>Contact | Ubirimi</title>
    <?php elseif ($page == 'sign-in'): ?>
        <title>My Account | Ubirimi</title>
    <?php elseif ($page == 'signup'): ?>
        <title>Sign Up | Ubirimi</title>
    <?php elseif ($page == 'newsletter'): ?>
        <title>Newsletter | Ubirimi</title>
    <?php elseif ($page == 'newsletter_success'): ?>
        <title>Successfully Subscribed to Our Newsletter | Ubirimi</title>
    <?php elseif ($page == 'account_home'): ?>
        <title>My Account - Home | Ubirimi</title>
    <?php elseif ($page == 'account_profile'): ?>
        <title>My Account - Profile | Ubirimi</title>
    <?php endif ?>
    <meta name="viewport" content="width=device-width">

    <script type="text/javascript" src="/js/vendor/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/js/main.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery.fancybox.pack.js?v=2.1.5"></script>
    <script type="text/javascript" src="/js/vendor/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="/js/vendor/jquery.cycle2.min.js"></script>
    <script type="text/javascript" src="/js/vendor/jquery.customSelect.min.js"></script>

    <link rel="stylesheet" href="/css/normalize.min.css">

    <link rel="stylesheet" href="/css/vendor/bootstrap.css">
    <link rel="stylesheet" href="/css/main.css?<?php echo UbirimiContainer::get()['app.version'] ?>">
    <link rel="stylesheet" href="/css/general.css?<?php echo UbirimiContainer::get()['app.version'] ?>">

    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery.fancybox.css?v=2.1.5" media="screen" />
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery.fancybox-buttons.css?v=1.0.5" media="screen" />

    <link rel="icon" type="image/ico" href="/img/site/bg.logo.png" />

    <meta name="keywords" content="free, project management software, bug tracking, bug tracker, issue tracking, issue tracker, agile module, project tracking software, project, management, tools, tool, issue, tracker, tracking, svn, hosting, issues, reporting, bug, bugs, jira alternative, jira similar, greenhopper alternative" />

    <!--[if lt IE 9]>
    <script>window.html5 || document.write('<script src="/js/vendor/html5shiv.js"><\/script>')</script>
    <![endif]-->
    <?php if (UbirimiContainer::get()['deploy.on_demand']): ?>
        <?php require_once __DIR__ . '/../../../Resources/views/_googleAnalytics.php' ?>
    <?php endif ?>
</head>