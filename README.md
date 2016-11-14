# WordPress Configurator

- So with this we now have a common config system for WordPress. It relies on the Set Env being properly set in your Apache (httpd.conf) or vhost config. Assuming you had a www.DOMAIN.com vhost like the following:

```
<VirtualHost *:80>
  ServerAdmin webops@DOMAIN.com
  ServerName www.DOMAIN.com
  ServerAlias DOMAIN.com
  DocumentRoot /data/DOMAIN.com/wproot/wordpress
  DirectoryIndex index.php
  Set Env ENVIRONMENT production
  php_value date.timezone "America/New_York"
  php_flag log_errors On
  php_value error_reporting 32767
  php_flag display_errors Off
  php_value error_log /data/DOMAIN.com/logs/php_error.log
  <Directory "/data/DOMAIN.com/wproot/wordpress">
     DirectoryIndex index.php
     Options FollowSymLinks
     AllowOverride All
     Require all granted
  </Directory>
  ErrorLog /data/DOMAIN.com/logs/error_log
  CustomLog /data/DOMAIN.com/logs/access_log common
</VirtualHost>

```

- The system also assumes that your WordPress web tree will live inside of the wproot. This is to work around the issue with composer projects not cohabitating well with other composer projects in the same directory.