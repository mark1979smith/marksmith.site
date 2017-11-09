FROM php:apache

ENV DEV_MODE false

# Set the working directory to /app
WORKDIR /var/www

COPY . /var/www

# SOFTWARE REQS
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install git -y

# APACHE MODULES
RUN a2enmod rewrite

# Setup default Vhost (also removing the need of .htaccess files in the app
RUN echo "PFZpcnR1YWxIb3N0ICo6ODA+DQogICAgICAgICMgVGhlIFNlcnZlck5hbWUgZGlyZWN0aXZlIHNldHMgdGhlIHJlcXVlc3Qgc2NoZW1lLCBob3N0bmFtZSBhbmQgcG9ydCB0aCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGF0DQogICAgICAgICMgdGhlIHNlcnZlciB1c2VzIHRvIGlkZW50aWZ5IGl0c2VsZi4gVGhpcyBpcyB1c2VkIHdoZW4gY3JlYXRpbmcNCiAgICAgICAgIyByZWRpcmVjdGlvbiBVUkxzLiBJbiB0aGUgY29udGV4dCBvZiB2aXJ0dWFsIGhvc3RzLCB0aGUgU2VydmVyTmFtZQ0KICAgICAgICAjIHNwZWNpZmllcyB3aGF0IGhvc3RuYW1lIG11c3QgYXBwZWFyIGluIHRoZSByZXF1ZXN0J3MgSG9zdDogaGVhZGVyIHRvDQogICAgICAgICMgbWF0Y2ggdGhpcyB2aXJ0dWFsIGhvc3QuIEZvciB0aGUgZGVmYXVsdCB2aXJ0dWFsIGhvc3QgKHRoaXMgZmlsZSkgdGhpcw0KICAgICAgICAjIHZhbHVlIGlzIG5vdCBkZWNpc2l2ZSBhcyBpdCBpcyB1c2VkIGFzIGEgbGFzdCByZXNvcnQgaG9zdCByZWdhcmRsZXNzLg0KICAgICAgICAjIEhvd2V2ZXIsIHlvdSBtdXN0IHNldCBpdCBmb3IgYW55IGZ1cnRoZXIgdmlydHVhbCBob3N0IGV4cGxpY2l0bHkuDQogICAgICAgICNTZXJ2ZXJOYW1lIHd3dy5leGFtcGxlLmNvbQ0KDQogICAgICAgIFNlcnZlckFkbWluIGhvc3RpbmdAbWFya3NtaXRoLmVtYWlsDQogICAgICAgIERvY3VtZW50Um9vdCAvdmFyL3d3dy93ZWINCg0KICAgICAgICAjIEF2YWlsYWJsZSBsb2dsZXZlbHM6IHRyYWNlOCwgLi4uLCB0cmFjZTEsIGRlYnVnLCBpbmZvLCBub3RpY2UsIHdhcm4sDQogICAgICAgICMgZXJyb3IsIGNyaXQsIGFsZXJ0LCBlbWVyZy4NCiAgICAgICAgIyBJdCBpcyBhbHNvIHBvc3NpYmxlIHRvIGNvbmZpZ3VyZSB0aGUgbG9nbGV2ZWwgZm9yIHBhcnRpY3VsYXINCiAgICAgICAgIyBtb2R1bGVzLCBlLmcuDQogICAgICAgICNMb2dMZXZlbCBpbmZvIHNzbDp3YXJuDQoNCiAgICAgICAgRXJyb3JMb2cgJHtBUEFDSEVfTE9HX0RJUn0vZXJyb3IubG9nDQogICAgICAgIEN1c3RvbUxvZyAke0FQQUNIRV9MT0dfRElSfS9hY2Nlc3MubG9nIGNvbWJpbmVkDQoNCiAgICAgICAgIyBGb3IgbW9zdCBjb25maWd1cmF0aW9uIGZpbGVzIGZyb20gY29uZi1hdmFpbGFibGUvLCB3aGljaCBhcmUNCiAgICAgICAgIyBlbmFibGVkIG9yIGRpc2FibGVkIGF0IGEgZ2xvYmFsIGxldmVsLCBpdCBpcyBwb3NzaWJsZSB0bw0KICAgICAgICAjIGluY2x1ZGUgYSBsaW5lIGZvciBvbmx5IG9uZSBwYXJ0aWN1bGFyIHZpcnR1YWwgaG9zdC4gRm9yIGV4YW1wbGUgdGhlDQogICAgICAgICMgZm9sbG93aW5nIGxpbmUgZW5hYmxlcyB0aGUgQ0dJIGNvbmZpZ3VyYXRpb24gZm9yIHRoaXMgaG9zdCBvbmx5DQogICAgICAgICMgYWZ0ZXIgaXQgaGFzIGJlZW4gZ2xvYmFsbHkgZGlzYWJsZWQgd2l0aCAiYTJkaXNjb25mIi4NCiAgICAgICAgI0luY2x1ZGUgY29uZi1hdmFpbGFibGUvc2VydmUtY2dpLWJpbi5jb25mDQoNCiAgICAgICAgPERpcmVjdG9yeSAiL3Zhci93d3cvd2ViIj4NCiAgICAgICAgICAgICMgVXNlIHRoZSBmcm9udCBjb250cm9sbGVyIGFzIGluZGV4IGZpbGUuIEl0IHNlcnZlcyBhcyBhIGZhbGxiYWNrIHNvbHV0aW9uIHdoZW4NCiAgICAgICAgICAgICMgZXZlcnkgb3RoZXIgcmV3cml0ZS9yZWRpcmVjdCBmYWlscyAoZS5nLiBpbiBhbiBhbGlhc2VkIGVudmlyb25tZW50IHdpdGhvdXQNCiAgICAgICAgICAgICMgbW9kX3Jld3JpdGUpLiBBZGRpdGlvbmFsbHksIHRoaXMgcmVkdWNlcyB0aGUgbWF0Y2hpbmcgcHJvY2VzcyBmb3IgdGhlDQogICAgICAgICAgICAjIHN0YXJ0IHBhZ2UgKHBhdGggIi8iKSBiZWNhdXNlIG90aGVyd2lzZSBBcGFjaGUgd2lsbCBhcHBseSB0aGUgcmV3cml0aW5nIHJ1bGVzDQogICAgICAgICAgICAjIHRvIGVhY2ggY29uZmlndXJlZCBEaXJlY3RvcnlJbmRleCBmaWxlIChlLmcuIGluZGV4LnBocCwgaW5kZXguaHRtbCwgaW5kZXgucGwpLg0KICAgICAgICAgICAgRGlyZWN0b3J5SW5kZXggYXBwLnBocA0KDQogICAgICAgICAgICAjIEJ5IGRlZmF1bHQsIEFwYWNoZSBkb2VzIG5vdCBldmFsdWF0ZSBzeW1ib2xpYyBsaW5rcyBpZiB5b3UgZGlkIG5vdCBlbmFibGUgdGhpcw0KICAgICAgICAgICAgIyBmZWF0dXJlIGluIHlvdXIgc2VydmVyIGNvbmZpZ3VyYXRpb24uIFVuY29tbWVudCB0aGUgZm9sbG93aW5nIGxpbmUgaWYgeW91DQogICAgICAgICAgICAjIGluc3RhbGwgYXNzZXRzIGFzIHN5bWxpbmtzIG9yIGlmIHlvdSBleHBlcmllbmNlIHByb2JsZW1zIHJlbGF0ZWQgdG8gc3ltbGlua3MNCiAgICAgICAgICAgICMgd2hlbiBjb21waWxpbmcgTEVTUy9TYXNzL0NvZmZlU2NyaXB0IGFzc2V0cy4NCiAgICAgICAgICAgICMgT3B0aW9ucyBGb2xsb3dTeW1saW5rcw0KDQogICAgICAgICAgICAjIERpc2FibGluZyBNdWx0aVZpZXdzIHByZXZlbnRzIHVud2FudGVkIG5lZ290aWF0aW9uLCBlLmcuICIvYXBwIiBzaG91bGQgbm90IHJlc29sdmUNCiAgICAgICAgICAgICMgdG8gdGhlIGZyb250IGNvbnRyb2xsZXIgIi9hcHAucGhwIiBidXQgYmUgcmV3cml0dGVuIHRvICIvYXBwLnBocC9hcHAiLg0KICAgICAgICAgICAgPElmTW9kdWxlIG1vZF9uZWdvdGlhdGlvbi5jPg0KICAgICAgICAgICAgICAgIE9wdGlvbnMgLU11bHRpVmlld3MNCiAgICAgICAgICAgIDwvSWZNb2R1bGU+DQoNCiAgICAgICAgICAgIDxJZk1vZHVsZSBtb2RfcmV3cml0ZS5jPg0KICAgICAgICAgICAgICAgIFJld3JpdGVFbmdpbmUgT24NCg0KICAgICAgICAgICAgICAgICMgRGV0ZXJtaW5lIHRoZSBSZXdyaXRlQmFzZSBhdXRvbWF0aWNhbGx5IGFuZCBzZXQgaXQgYXMgZW52aXJvbm1lbnQgdmFyaWFibGUuDQogICAgICAgICAgICAgICAgIyBJZiB5b3UgYXJlIHVzaW5nIEFwYWNoZSBhbGlhc2VzIHRvIGRvIG1hc3MgdmlydHVhbCBob3N0aW5nIG9yIGluc3RhbGxlZCB0aGUNCiAgICAgICAgICAgICAgICAjIHByb2plY3QgaW4gYSBzdWJkaXJlY3RvcnksIHRoZSBiYXNlIHBhdGggd2lsbCBiZSBwcmVwZW5kZWQgdG8gYWxsb3cgcHJvcGVyDQogICAgICAgICAgICAgICAgIyByZXNvbHV0aW9uIG9mIHRoZSBhcHAucGhwIGZpbGUgYW5kIHRvIHJlZGlyZWN0IHRvIHRoZSBjb3JyZWN0IFVSSS4gSXQgd2lsbA0KICAgICAgICAgICAgICAgICMgd29yayBpbiBlbnZpcm9ubWVudHMgd2l0aG91dCBwYXRoIHByZWZpeCBhcyB3ZWxsLCBwcm92aWRpbmcgYSBzYWZlLCBvbmUtc2l6ZQ0KICAgICAgICAgICAgICAgICMgZml0cyBhbGwgc29sdXRpb24uIEJ1dCBhcyB5b3UgZG8gbm90IG5lZWQgaXQgaW4gdGhpcyBjYXNlLCB5b3UgY2FuIGNvbW1lbnQNCiAgICAgICAgICAgICAgICAjIHRoZSBmb2xsb3dpbmcgMiBsaW5lcyB0byBlbGltaW5hdGUgdGhlIG92ZXJoZWFkLg0KICAgICAgICAgICAgICAgIFJld3JpdGVDb25kICV7UkVRVUVTVF9VUkl9OjokMSBeKC8uKykvKC4qKTo6XDIkDQogICAgICAgICAgICAgICAgUmV3cml0ZVJ1bGUgXiguKikgLSBbRT1CQVNFOiUxXQ0KDQogICAgICAgICAgICAgICAgIyBTZXRzIHRoZSBIVFRQX0FVVEhPUklaQVRJT04gaGVhZGVyIHJlbW92ZWQgYnkgQXBhY2hlDQogICAgICAgICAgICAgICAgUmV3cml0ZUNvbmQgJXtIVFRQOkF1dGhvcml6YXRpb259IC4NCiAgICAgICAgICAgICAgICBSZXdyaXRlUnVsZSBeIC0gW0U9SFRUUF9BVVRIT1JJWkFUSU9OOiV7SFRUUDpBdXRob3JpemF0aW9ufV0NCg0KICAgICAgICAgICAgICAgICMgUmVkaXJlY3QgdG8gVVJJIHdpdGhvdXQgZnJvbnQgY29udHJvbGxlciB0byBwcmV2ZW50IGR1cGxpY2F0ZSBjb250ZW50DQogICAgICAgICAgICAgICAgIyAod2l0aCBhbmQgd2l0aG91dCBgL2FwcC5waHBgKS4gT25seSBkbyB0aGlzIHJlZGlyZWN0IG9uIHRoZSBpbml0aWFsDQogICAgICAgICAgICAgICAgIyByZXdyaXRlIGJ5IEFwYWNoZSBhbmQgbm90IG9uIHN1YnNlcXVlbnQgY3ljbGVzLiBPdGhlcndpc2Ugd2Ugd291bGQgZ2V0IGFuDQogICAgICAgICAgICAgICAgIyBlbmRsZXNzIHJlZGlyZWN0IGxvb3AgKHJlcXVlc3QgLT4gcmV3cml0ZSB0byBmcm9udCBjb250cm9sbGVyIC0+DQogICAgICAgICAgICAgICAgIyByZWRpcmVjdCAtPiByZXF1ZXN0IC0+IC4uLikuDQogICAgICAgICAgICAgICAgIyBTbyBpbiBjYXNlIHlvdSBnZXQgYSAidG9vIG1hbnkgcmVkaXJlY3RzIiBlcnJvciBvciB5b3UgYWx3YXlzIGdldCByZWRpcmVjdGVkDQogICAgICAgICAgICAgICAgIyB0byB0aGUgc3RhcnQgcGFnZSBiZWNhdXNlIHlvdXIgQXBhY2hlIGRvZXMgbm90IGV4cG9zZSB0aGUgUkVESVJFQ1RfU1RBVFVTDQogICAgICAgICAgICAgICAgIyBlbnZpcm9ubWVudCB2YXJpYWJsZSwgeW91IGhhdmUgMiBjaG9pY2VzOg0KICAgICAgICAgICAgICAgICMgLSBkaXNhYmxlIHRoaXMgZmVhdHVyZSBieSBjb21tZW50aW5nIHRoZSBmb2xsb3dpbmcgMiBsaW5lcyBvcg0KICAgICAgICAgICAgICAgICMgLSB1c2UgQXBhY2hlID49IDIuMy45IGFuZCByZXBsYWNlIGFsbCBMIGZsYWdzIGJ5IEVORCBmbGFncyBhbmQgcmVtb3ZlIHRoZQ0KICAgICAgICAgICAgICAgICMgICBmb2xsb3dpbmcgUmV3cml0ZUNvbmQgKGJlc3Qgc29sdXRpb24pDQogICAgICAgICAgICAgICAgUmV3cml0ZUNvbmQgJXtFTlY6UkVESVJFQ1RfU1RBVFVTfSBeJA0KICAgICAgICAgICAgICAgIFJld3JpdGVSdWxlIF5hcHBcLnBocCg/Oi8oLiopfCQpICV7RU5WOkJBU0V9LyQxIFtSPTMwMSxMXQ0KDQogICAgICAgICAgICAgICAgIyBJZiB0aGUgcmVxdWVzdGVkIGZpbGVuYW1lIGV4aXN0cywgc2ltcGx5IHNlcnZlIGl0Lg0KICAgICAgICAgICAgICAgICMgV2Ugb25seSB3YW50IHRvIGxldCBBcGFjaGUgc2VydmUgZmlsZXMgYW5kIG5vdCBkaXJlY3Rvcmllcy4NCiAgICAgICAgICAgICAgICBSZXdyaXRlQ29uZCAle1JFUVVFU1RfRklMRU5BTUV9IC1mDQogICAgICAgICAgICAgICAgUmV3cml0ZVJ1bGUgXiAtIFtMXQ0KDQogICAgICAgICAgICAgICAgIyBSZXdyaXRlIGFsbCBvdGhlciBxdWVyaWVzIHRvIHRoZSBmcm9udCBjb250cm9sbGVyLg0KICAgICAgICAgICAgICAgIFJld3JpdGVSdWxlIF4gJXtFTlY6QkFTRX0vYXBwLnBocCBbTF0NCiAgICAgICAgICAgIDwvSWZNb2R1bGU+DQoNCiAgICAgICAgICAgIDxJZk1vZHVsZSAhbW9kX3Jld3JpdGUuYz4NCiAgICAgICAgICAgICAgICA8SWZNb2R1bGUgbW9kX2FsaWFzLmM+DQogICAgICAgICAgICAgICAgICAgICMgV2hlbiBtb2RfcmV3cml0ZSBpcyBub3QgYXZhaWxhYmxlLCB3ZSBpbnN0cnVjdCBhIHRlbXBvcmFyeSByZWRpcmVjdCBvZg0KICAgICAgICAgICAgICAgICAgICAjIHRoZSBzdGFydCBwYWdlIHRvIHRoZSBmcm9udCBjb250cm9sbGVyIGV4cGxpY2l0bHkgc28gdGhhdCB0aGUgd2Vic2l0ZQ0KICAgICAgICAgICAgICAgICAgICAjIGFuZCB0aGUgZ2VuZXJhdGVkIGxpbmtzIGNhbiBzdGlsbCBiZSB1c2VkLg0KICAgICAgICAgICAgICAgICAgICBSZWRpcmVjdE1hdGNoIDMwMiBeLyQgL2FwcC5waHAvDQogICAgICAgICAgICAgICAgICAgICMgUmVkaXJlY3RUZW1wIGNhbm5vdCBiZSB1c2VkIGluc3RlYWQNCiAgICAgICAgICAgICAgICA8L0lmTW9kdWxlPg0KICAgICAgICAgICAgPC9JZk1vZHVsZT4NCiAgICAgICAgPC9EaXJlY3Rvcnk+DQoNCiAgICAgICAgPERpcmVjdG9yeSAiL3Zhci93d3cvc3JjIj4NCiAgICAgICAgICAgIDxJZk1vZHVsZSBtb2RfYXV0aHpfY29yZS5jPg0KICAgICAgICAgICAgICAgIFJlcXVpcmUgYWxsIGRlbmllZA0KICAgICAgICAgICAgPC9JZk1vZHVsZT4NCiAgICAgICAgICAgIDxJZk1vZHVsZSAhbW9kX2F1dGh6X2NvcmUuYz4NCiAgICAgICAgICAgICAgICBPcmRlciBkZW55LGFsbG93DQogICAgICAgICAgICAgICAgRGVueSBmcm9tIGFsbA0KICAgICAgICAgICAgPC9JZk1vZHVsZT4NCiAgICAgICAgPC9EaXJlY3Rvcnk+DQoNCiAgICAgICAgPERpcmVjdG9yeSAiL3Zhci93d3cvYXBwIj4NCiAgICAgICAgICAgIDxJZk1vZHVsZSBtb2RfYXV0aHpfY29yZS5jPg0KICAgICAgICAgICAgICAgIFJlcXVpcmUgYWxsIGRlbmllZA0KICAgICAgICAgICAgPC9JZk1vZHVsZT4NCiAgICAgICAgICAgIDxJZk1vZHVsZSAhbW9kX2F1dGh6X2NvcmUuYz4NCiAgICAgICAgICAgICAgICBPcmRlciBkZW55LGFsbG93DQogICAgICAgICAgICAgICAgRGVueSBmcm9tIGFsbA0KICAgICAgICAgICAgPC9JZk1vZHVsZT4NCg0KICAgICAgICA8L0RpcmVjdG9yeT4NCjwvVmlydHVhbEhvc3Q+DQo="  | base64 --decode > /etc/apache2/sites-enabled/000-default.conf

# Create Deployment User and group
# Change Apache User from www-data to deployuser
RUN groupadd deploygroup && \
    adduser --disabled-password --gecos ""  --ingroup deploygroup deployuser && \
    chgrp deploygroup /var/www -R && \
    chown deployuser /var/www -R && \
    sed -i 's/${APACHE_RUN_USER:=www-data}/${APACHE_RUN_USER:=deployuser}/g' /etc/apache2/envvars && \
    sed -i 's/${APACHE_RUN_GROUP:=www-data}/${APACHE_RUN_GROUP:=deploygroup}/g' /etc/apache2/envvars

# Change owner to avoid running as root
USER deployuser

# RUN COMPOSER to generate parameters.yml file
RUN /usr/local/bin/php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    /usr/local/bin/php -r "copy('https://composer.github.io/installer.sig', 'composer-installer.sig');" && \
    /usr/local/bin/php -r "if (hash_file('SHA384', 'composer-setup.php') === trim(file_get_contents('composer-installer.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    /usr/local/bin/php composer-setup.php && \
    /usr/local/bin/php -r "unlink('composer-setup.php');" && \
    /usr/local/bin/php -r "unlink('composer-installer.sig');" && \
    /usr/local/bin/php composer.phar install -n

# SET UP DEPLOYMENT KEY TO ALLOW GIT PULL
RUN  mkdir -p ~/.ssh && echo "c3NoLXJzYSBBQUFBQjNOemFDMXljMkVBQUFBREFRQUJBQUFDQVFEWTBla1hma2o1Y3BERFRUUW5tSGxzVDVUODBrbzByWUtKOW1BTmhqQ2Z4MS9Ja25RZzZTWGxUc3A2YkUzZXZnR2lwZkw5SlZqZ3pxWDVoUXhNVzdJSVd5UHpuSkxpK0hhYnE5ci9oTmtHWTUxcDBjWk5rZmNIMTJVWjM4NDBPUGhac3dpNVIxc1RZczdkZjE4eFpUdDk5SzdkZVBtSHowdFRhRDVGaFJVTWRlMXB0S2FLOCsxZEkwZHExL1psKzRvekZKWkhhQUkySWluN3A5SWFDaFdWREY0dm1kRmd1RXErczl4Z05LeER4Z0hXUExRSUhKU1M0NTBDeVFSdFV5S04rMGxOdit4ak5oYmY5N2NFTFkrc2JKSVh6N0doQ2xCSFJKU3o2RE5wWEkwQkZzY29ydVZjRkNidlVEeXlUS3c2SkkyNXVTMDM5d1BDVzFvT2dnbTl3RGQrZVcxandleXhzMWNKMDA1b0xxcWdHQ0NOUEZRcW9acHVUbzJEb3hNcllTVk42b2lNUXNBbEorYnpIaW1OTzRkQzVVcU5RYUliSkdQY25wcVBQd3Rnc1lrdDNQSENQdFFpNWtjcFh4VGc1a2lTdnRiaCs4b1R4emtjSWppTjZSZlU5N0tIS0Z3QW96dDRXMm96akR6NVgyd2lXbWpxNGJqMXlQdCsxWDdVbFR4WXlIL0kxbWg2S2dHdlY3dzdUTXBwcXlPUklGb1lubVVxZmpuNm5SMmp5UjRjei9JcHAvR05BRjVGOXlXY2MxdEpSS1Z0N0tHRGNiaUpPenUxN0ZNNVNXYll0U1dLcFRUTHcwR3BQUllqWDJ0anZIbWZ6bnZxRVlyM1lFK3U5djV5M0I2QmJERVZ3NEpYdmNUMEZDQlZxZU1YOHA1d2VrdVd0bWxhaHc9PSBtYXJrMTk3OXNtaXRoQGdvb2dsZW1haWwuY29tDQo=" | base64 --decode > ~/.ssh/id_rsa.pub

# Switch back to ROOT
USER root
