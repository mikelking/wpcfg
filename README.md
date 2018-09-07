# WordPress Configurator

Enables you to store your entire WordPress application configuration in an addressable object relative to the server environment. 

- The system also assumes that your WordPress web tree will live inside of the wproot. This is to work around the issue with composer projects not cohabitating well with other composer projects in the same directory.

- The system defaults to production and will therefore look for a production-conf.php which should extend the server-conf-base.php.

- Whatever value the apache ```ENVIRONMENT``` variable is set to will determine the config file prefix that this system will search for and attempt to load. For example if you add ```SetEnv ENVIRONMENT mike``` the system will load mike-conf.php if it exists. Corespondingly if it does not exist you will enjoy the dreaded White Screen of Death (WSoD).

- So with this we now have a common config system for WordPress. It relies on the SetEnv being properly set up in your Apache (httpd.conf) or vhost config. Assuming you had a www.DOMAIN.com and your setting up a production system the vhost would look like the following example:

```
<VirtualHost *:80>
  ServerAdmin webops@DOMAIN.com
  ServerName www.DOMAIN.com
  ServerAlias DOMAIN.com
  DocumentRoot /data/DOMAIN.com/wproot/wordpress
  DirectoryIndex index.php
  SetEnv ENVIRONMENT production
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
     ####
     # The following is an example of the WordPress MultiSite rewrite rules
     # that most people stuff in the under performing .htaccess file.
     # If you have access to the vhost then boost your perofrmance and ditch
     # the .htaccess
     # see https://codex.wordpress.org/htaccess
     #
     RewriteEngine On
     RewriteBase /
     RewriteRule ^index\.php$ - [L]

     # add a trailing slash to /wp-admin
     RewriteRule ^wp-admin$ wp-admin/ [R=301,L]

     RewriteCond %{REQUEST_FILENAME} -f [OR]
     RewriteCond %{REQUEST_FILENAME} -d
     RewriteRule ^ - [L]
     RewriteRule ^(wp-(content|admin|includes).*) $1 [L]
     RewriteRule ^(.*\.php)$ $1 [L]
     RewriteRule . index.php [L]
  </Directory>
  ErrorLog /data/DOMAIN.com/logs/error_log
  CustomLog /data/DOMAIN.com/logs/access_log common
</VirtualHost>

```


- Also if you are looking to really rock the vhost take a look at https://github.com/gregrickaby/The-Perfect-Apache-Configuration/blob/master/http.conf you will need to ensure taht mod expires and mod headers is activated.
 
- It also can detect if the site it is running on is a dev or test based on some domain name URL checks but this will be phased out. 
 
- However, when that the above is deprecated I will add a check for .local domains to more consistently support local development environments. In that case it will look for a local-conf.php.

### References: 

[GitHub Markdown basics](https://help.github.com/articles/basic-writing-and-formatting-syntax/)

[Semantic Versioning](https://semver.org/)
