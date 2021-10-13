FROM gitpod/workspace-mysql
RUN apt-get update && apt-get -y install redis
RUN service redis-server start
