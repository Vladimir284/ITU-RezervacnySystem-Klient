FROM mariadb:latest

ENV MARIADB_ROOT_PASSWORD admin
ENV MARIADB_DATABASE db

#COPY Tvorba_databaze.sql /tmp/create.sql
COPY Tvorba_databaze.sql /docker-entrypoint-initdb.d/1.sql
#CMD ["mysqld", "--init-file=/tmp/create.sql"]
