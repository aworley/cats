## Welcome to the Case Automated Transfer System (CATS)! ##

### What is CATS? ###

CATS is a web application that is used to connect your A2J online interview to
your case management database. The A2J output file will be converted from XML
format to a format readable by your CSM.  The record is then transmitted
directly to your CMS database.

### Installing CATS ###

These instructions are for CentOS 7.

* Install Apache web server, PHP, mod_ssl, and MySQL or MariaDB.

* Install additional required PHP modules with the command:
	yum install php-mbstring php-xml

* Download the OCM software from https://github.com/aworley/cats

* Move the cats directory to /var/www/html/cats

* Point your browser to https://"your server IP address"/cats/

* Verify that the CATS page appears.

* The system is now ready to use.
