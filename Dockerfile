FROM d.thcen.com/php
ENV TZ Asia/Shanghai
WORKDIR /var/www/html
COPY sdk sdk
COPY data data
COPY core core
COPY qianye qianye
COPY index.php .
COPY favicon.ico .
RUN chmod -R 777 data