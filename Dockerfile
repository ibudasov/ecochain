FROM thatsamguy/trusty-php71

RUN apt-get update

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN mv composer.phar /usr/local/bin/composer
RUN php -r "unlink('composer-setup.php');"

RUN echo "date.timezone = Europe/Prague" >> /etc/php.ini

RUN adduser --disabled-password --gecos "" igor

RUN mkdir /var/www/ecochain
RUN chmod 777 /var/www/ecochain

RUN su - igor -c 'git clone https://github.com/ibudasov/ecochain.git /var/www/ecochain'
RUN su - igor -c 'cd /var/www/ecochain && composer install'
RUN su - igor -c 'cd /var/www/ecochain && bin/console assets:install'
RUN su - igor -c 'SYMFONY_ENV="dev" cd /var/www/ecochain && bin/console server:run'

EXPOSE 8000

CMD ["/bin/bash"]
