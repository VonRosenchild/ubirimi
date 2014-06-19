<ul class="nav nav-tabs" style="padding: 0px;">
    <li <?php if ('statistics' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration">Statistics</a>
    </li>
    <li <?php if ('active_clients_last_month' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/active-clients">Active Clients</a>
    </li>

    <li <?php if ('clients' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/clients">Clients</a>
    </li>

    <li <?php if ('projects' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/projects">Projects</a>
    </li>

    <li <?php if ('users' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/users">Users</a>
    </li>

    <li <?php if ('svns' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/svn">SVN Repositories</a>
    </li>

    <li <?php if ('issues' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/issues">Issues</a>
    </li>

    <li <?php if ('agile' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/agile-boards">Agile Boards</a>
    </li>

    <li <?php if ('sprints' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/agile-sprints">Agile Sprints</a>
    </li>

    <li <?php if ('spaces' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/spaces">Spaces</a>
    </li>

    <li <?php if ('entity' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/entities">Entities</a>
    </li>
    <li <?php if ('mail_queue' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/mail-queue">Mail Queue</a>
    </li>
    <li <?php if ('calendar' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/calendars">Calendars</a>
    </li>
    <li <?php if ('log' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/logs">Client Logs</a>
    </li>
    <li <?php if ('apache_log' == $selectedOption): ?>class="active"<?php endif ?>>
        <a href="/administration/apache-logs">Apache Logs</a>
    </li>
</ul>