FROM alpine:3.11

RUN echo 'http://dl-cdn.alpinelinux.org/alpine/v3.6/main' >> /etc/apk/repositories
RUN echo 'http://dl-cdn.alpinelinux.org/alpine/v3.6/community' >> /etc/apk/repositories
RUN apk update


RUN apk add mongodb --no-cache

VOLUME /data/db
EXPOSE 27017 28017

COPY run.sh /root
RUN chmod a+x /root/run.sh
ENTRYPOINT [ "/root/run.sh" ]
CMD [ "mongod", "--bind_ip", "0.0.0.0" ]