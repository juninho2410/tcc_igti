FROM webdevops/php-apache-dev:7.4

WORKDIR /app-code

RUN git clone https://github.com/juninho2410/tcc_igti.git

WORKDIR /app-code/tcc_igti

ENV MYSQL_DB_HOST mysql
ENV MYSQL_DB_PORT 3306
ENV MYSQL_DB_USER root
ENV MYSQL_DB_PASSWORD admin
ENV MYSQL_DB_NAME tcc
ENV CI_ENVIRONMENT development

RUN chown -R ${APPLICATION_USER}:${APPLICATION_GROUP} . && chmod -R go+rwx writable

RUN cp env .env && \
      sed -e 's/# CI_ENVIRONMENT = production/CI_ENVIRONMENT = '${CI_ENVIRONMENT}'/' \
 .env > .env_env \
 &&   sed -e 's/# database.default.hostname = localhost/database.default.hostname = '${MYSQL_DB_HOST}'/' \
 .env_env > .env_host \
 &&   sed -e 's/# database.default.database = ci4/database.default.database = '${MYSQL_DB_NAME}'/' \
 .env_host > .env_db \
 &&   sed -e 's/# database.default.username = root/database.default.username = '${MYSQL_DB_USER}'/' \
.env_db > .env_u \
 &&   sed -e 's/# database.default.password = root/database.default.password = '${MYSQL_DB_PASSWORD}'/' \
 .env_u > .env \
 && rm -rf .env_env .env_host .env_db .env_u
WORKDIR /

RUN rm -rf /app && ln -s app-code/tcc_igti/public /app


