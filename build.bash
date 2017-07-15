#!/bin/bash
composer install --no-dev
docker build --file ./Dockerfiles/database.Dockerfile --tag salute/database .
docker build --file ./Dockerfiles/streamer.Dockerfile --tag salute/streamer .
docker build --file ./Dockerfiles/parser.Dockerfile --tag salute/parser .
composer install
