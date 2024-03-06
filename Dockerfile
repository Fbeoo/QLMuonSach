FROM php:8.2-fpm
ARG user=myuser
ARG uid

# Cài đặt các dependencies cần thiết
RUN apt-get update && \
    apt-get install -y \
        git \
        curl \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        zlib1g-dev \
        libzip-dev \
        supervisor && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Cài đặt các extension PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Tạo người dùng và cấu hình Composer
RUN useradd -G www-data,root -u $uid -d /home/$user $user && \
    mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Cấu hình Supervisor
# Cấu hình Supervisor
RUN mkdir -p /etc/supervisor/conf.d && \
    mkdir -p /var/log/supervisor && \
    mkdir -p /etc/supervisor/logs

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN apt-get update && apt-get -y install cron

COPY crontab.sh /etc/cron.d/crontab.sh
RUN chmod 777 /etc/cron.d/crontab.sh

RUN chmod 777 /run

RUN chmod gu+rw /var/run &&\
    chmod gu+s /usr/sbin/cron

RUN touch /var/log/cron.log && chmod 777 /var/log/cron.log

# Cấu hình cron để ghi log vào tệp cron.log
RUN echo "* * * * * root echo 'Cron job is running' >> /var/log/cron.log" >> /etc/crontab

RUN apt-get update && apt-get install -y nano


#RUN chmod 644 /etc/cron.d/crontab
#RUN chmod 644 /etc/crontab
#
#RUN chmod 777 /run
#

WORKDIR /var/www
USER $user
