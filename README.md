# Installation

Docker build contexts must be relative to project root.

    docker build --file Docker/StreamingApi/php/Dockerfile --tag salute/consumer .
    docker build --file Docker/StreamingApi/mysql/Dockerfile --tag salute/database .
