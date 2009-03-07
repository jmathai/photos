#!/bin/sh

cp /etc/my.cnf /www/backups/www/config
cp /usr/local/lib/php.ini /www/backups/www/config
cp /usr/local/apache/conf/httpd.conf /www/backups/www/config
crontab -l > /www/backups/www/config/crontab.txt