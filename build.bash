#!/bin/bash
composer install --no-dev
docker build --file Docker/StreamingApi/php/Dockerfile --tag salute/consumer .
docker build --file Docker/StreamingApi/mysql/Dockerfile --tag salute/database .
composer install
