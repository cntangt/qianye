FROM d.thcen.com/php
ENV TZ Asia/Shanghai
WORKDIR /var/www/html
COPY sdk sdk
COPY data data
COPY core core
COPY qianye qianye
COPY index.php .
COPY favicon.ico .
RUN chmod -R 755 data
RUN chwon -R www-data:www-data /var/www/html/data \
    && chmod -R 755 /var/www/html/data