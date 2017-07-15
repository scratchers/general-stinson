FROM ubuntu:16.04

RUN apt-get update \
    && apt-get install -y php7.0-cli php7.0-curl php7.0-mysql php7.0-mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY src /srv/src
COPY public /srv/public
COPY vendor /srv/vendor

CMD [ "php", "/srv/public/parse.php" ]
