What is Ubirimi?
-----------------

Ubirimi is a productivity platform that offers open source tools. It is written with speed and
flexibility in mind. A commercial fork can be found at https://www.ubirimi.com. All profit is donated to charity.

Requirements
------------
- Apache or nginx
- Ubirimi is only supported on PHP 5.5.0 and up.
- Be warned that PHP versions before 5.3.8 are known to be buggy and might not work for you
- MySQL 5.0 or above
- if you go with Apache you must install mod_rewrite module

Products available
------------
1. Yongo - Track and manage the issues, bugs, tasks, deadlines, code, hours.
2. Agile - The power of Agile: planning, estimating and visualizing team activity.
3. Helpdesk - Powerful solution for any organization. Keep in touch with your customers.
4. Documentador - Content Creation, Collaboration & Knowledge Sharing software for teams.
5. SVN Hosting - Reliable, private hosting for your projects with unlimited users.
6. Events - Plan and keep track of people, projects and events. A complete calendar application.
7. QuickNotes - A note application

Installation
------------

- download the source code
- `php composer.phar install`
- import an empty database structure
- set your Apache virtual host configuration. An example can be found below:

```
<VirtualHost *:80>
  ServerName ubirimi_net.lan
  DocumentRoot "c:/www/ubirimi-web/web"
  ServerAlias demo.ubirimi_net.lan
  DirectoryIndex index.php

  <Directory "c:/www/ubirimi-web/web">
      AllowOverride All
      Allow from ubirimi_net.lan
  </Directory>

  Alias /assets c:/www/ubirimi-web/assets
  <Directory "c:/www/ubirimi-web/assets">
	AllowOverride All
	Allow from All
  </Directory>
</VirtualHost>
```

Documentation
-------------

not much available so far

Contributing
------------

Ubirimi is an open source, community-driven project. If you would like to contribute just send pull requests.

Copyright and license
---------------------

Ubirimi is distributed under the terms of the GNU General Public License version 2.0
