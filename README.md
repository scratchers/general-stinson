# Installation

Docker build contexts must be relative to project root.

    docker build --file docker/StreamingApi/php/Dockerfile --tag salute/consumer .
    docker build --file docker/StreamingApi/mysql/Dockerfile --tag salute/database .
