FROM webdevops/php-apache-dev:7.4

WORKDIR /app-code

RUN git clone https://github.com/juninho2410/tcc_igti.git

WORKDIR /app-code/tcc_igti

ENV MYSQL_DB_HOST localhost
ENV MYSQL_DB_PORT 3306
ENV MYSQL_DB_USER admin
ENV MYSQL_DB_PASSWORD admin
ENV MYSQL_DB_NAME tcc

RUN cp env .env && \
      sed -e 's/# database.default.database = ci4/database.default.database = '${MYSQL_DB_NAME}'/' \
 .env > .env_h \
 &&   sed -e 's/# database.default.username = root/database.default.username = '${MYSQL_DB_USER}'/' \
.env_h > .env_u \
 &&   sed -e 's/# database.default.password = root/database.default.password = '${MYSQL_DB_PASSWORD}'/' \
 .env_u > .env \
 && rm -rf .env_h .env_u
WORKDIR /

RUN rm -rf /app && ln -s app-code/tcc_igti/public app


