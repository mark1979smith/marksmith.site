FROM php-zendserver:latest

# Set the working directory to /app
WORKDIR /var/www

ADD . /var/www

# REMOVE default directory
RUN rm -rf /var/www/html
RUN ln -s /var/www/web /var/www/html 

# SSL setup
RUN sudo a2enmod ssl 
RUN sudo a2ensite default-ssl

# VHOSTS SETUP - to set AllowOverride
# SSL
RUN echo "PFZpcnR1YWxIb3N0ICo6NDQzPg0KICAgICMgRG9jdW1lbnRSb290IHNob3VsZCBhbHdheXMgYmUgc2V0IHRvIC92YXIvd3d3L2h0bWwNCiAgICAjIFRoZSB2YWx1ZSBmb3IgL3Zhci93d3cvaHRtbCBpcyBkZWZpbmVkIGJ5IFplbmQgZGVwbG95bWVudCBkYWVtb24NCiAgICAjIFlvdSBjYW4gdXNlIHRoZSBwbGFjZWhvbGRlciAvdmFyL3d3dy9odG1sIGluIGRpZmZlcmVudCBkaXJlY3RpdmVzDQogICAgRG9jdW1lbnRSb290ICIvdmFyL3d3dy9odG1sIg0KICAgIDxEaXJlY3RvcnkgIi92YXIvd3d3L2h0bWwiPg0KICAgICAgICBPcHRpb25zICtJbmRleGVzICtGb2xsb3dTeW1MaW5rcw0KICAgICAgICBEaXJlY3RvcnlJbmRleCBpbmRleC5waHANCiAgICAgICAgT3JkZXIgYWxsb3csZGVueQ0KICAgICAgICBBbGxvdyBmcm9tIGFsbA0KICAgICAgICBBbGxvd092ZXJyaWRlIEFsbA0KICAgICAgICBSZXF1aXJlIGFsbCBncmFudGVkDQogICAgPC9EaXJlY3Rvcnk+DQoNCiAgICBTU0xFbmdpbmUgb24NCiAgICBTU0xDZXJ0aWZpY2F0ZUZpbGUgIi9zc2wvY2VydC5wZW0iDQogICAgU1NMQ2VydGlmaWNhdGVLZXlGaWxlICIvc3NsL3ByaXZrZXkucGVtIg0KICAgIFNTTENlcnRpZmljYXRlQ2hhaW5GaWxlICIvc3NsL2NoYWluLnBlbSINCg0KICAgIFNlcnZlck5hbWUgbWUubWFya3NtaXRoLnNpdGU6NDQzDQogICAgU2V0RW52SWYgQXV0aG9yaXphdGlvbiAiKC4qKSIgSFRUUF9BVVRIT1JJWkFUSU9OPSQxDQoNCiAgICAjIGluY2x1ZGUgdGhlIGZvbGRlciBjb250YWluaW5nIHRoZSB2aG9zdCBhbGlhc2VzIGZvciB6ZW5kIHNlcnZlciBkZXBsb3ltZW50DQogICAgSW5jbHVkZU9wdGlvbmFsICIvdXNyL2xvY2FsL3plbmQvZXRjL3NpdGVzLmQvaHR0cHMvbWUubWFya3NtaXRoLnNpdGUvNDQzLyouY29uZiINCg0KPC9WaXJ0dWFsSG9zdD4NCg=="  | base64 --decode > /usr/local/zend/etc/sites.d/vhost_https_me.marksmith.site_443.conf
# NON-SSL Redirect
RUN echo "PFZpcnR1YWxIb3N0ICo6ODA+DQogICAgU2VydmVyTmFtZSBtZS5tYXJrc21pdGguc2l0ZQ0KICAgIFJlZGlyZWN0IC8gaHR0cHM6Ly9tZS5tYXJrc21pdGguc2l0ZQ0KPC9WaXJ0dWFsSG9zdD4="  | base64 --decode > /usr/local/zend/etc/sites.d/vhost_https_me.marksmith.site.conf

# SET PERMISSIONS to apache where needed
RUN chgrp www-data /var/www/var/cache/ -R && chmod 0775 /var/www/var/cache/ -R
RUN chgrp www-data /var/www/var/logs/ -R && chmod 0775 /var/www/var/logs/ -R
RUN chgrp www-data /var/www/var/sessions/ -R && chmod 0775 /var/www/var/sessions/ -R

# RUN COMPOSER to generate parameters.yml file
RUN /usr/local/zend/bin/php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN /usr/local/zend/bin/php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN /usr/local/zend/bin/php composer-setup.php
RUN /usr/local/zend/bin/php -r "unlink('composer-setup.php');"
RUN /usr/local/zend/bin/php composer.phar install -n -q

# SET UP DEPLOYMENT KEY TO ALLOW GIT PULL
RUN  mkdir -p ~/.ssh && echo "c3NoLXJzYSBBQUFBQjNOemFDMXljMkVBQUFBREFRQUJBQUFDQVFEWTBla1hma2o1Y3BERFRUUW5tSGxzVDVUODBrbzByWUtKOW1BTmhqQ2Z4MS9Ja25RZzZTWGxUc3A2YkUzZXZnR2lwZkw5SlZqZ3pxWDVoUXhNVzdJSVd5UHpuSkxpK0hhYnE5ci9oTmtHWTUxcDBjWk5rZmNIMTJVWjM4NDBPUGhac3dpNVIxc1RZczdkZjE4eFpUdDk5SzdkZVBtSHowdFRhRDVGaFJVTWRlMXB0S2FLOCsxZEkwZHExL1psKzRvekZKWkhhQUkySWluN3A5SWFDaFdWREY0dm1kRmd1RXErczl4Z05LeER4Z0hXUExRSUhKU1M0NTBDeVFSdFV5S04rMGxOdit4ak5oYmY5N2NFTFkrc2JKSVh6N0doQ2xCSFJKU3o2RE5wWEkwQkZzY29ydVZjRkNidlVEeXlUS3c2SkkyNXVTMDM5d1BDVzFvT2dnbTl3RGQrZVcxandleXhzMWNKMDA1b0xxcWdHQ0NOUEZRcW9acHVUbzJEb3hNcllTVk42b2lNUXNBbEorYnpIaW1OTzRkQzVVcU5RYUliSkdQY25wcVBQd3Rnc1lrdDNQSENQdFFpNWtjcFh4VGc1a2lTdnRiaCs4b1R4emtjSWppTjZSZlU5N0tIS0Z3QW96dDRXMm96akR6NVgyd2lXbWpxNGJqMXlQdCsxWDdVbFR4WXlIL0kxbWg2S2dHdlY3dzdUTXBwcXlPUklGb1lubVVxZmpuNm5SMmp5UjRjei9JcHAvR05BRjVGOXlXY2MxdEpSS1Z0N0tHRGNiaUpPenUxN0ZNNVNXYll0U1dLcFRUTHcwR3BQUllqWDJ0anZIbWZ6bnZxRVlyM1lFK3U5djV5M0I2QmJERVZ3NEpYdmNUMEZDQlZxZU1YOHA1d2VrdVd0bWxhaHc9PSBtYXJrMTk3OXNtaXRoQGdvb2dsZW1haWwuY29tDQo=" | base64 --decode > ~/.ssh/id_rsa.pub
