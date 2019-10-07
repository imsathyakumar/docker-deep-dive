#First Dockerfile
FROM openjdk:8-jre-alpine
LABEL maintainer "Sathya Kumar"

ARG JAR_FILE='hello-world-app-0.0.1-SNAPSHOT.jar'
ARG JAR_LOC='.'
ARG TARGET_JAR='hello-world-app.jar'
ENV TARGET_JAR=$TARGET_JAR

EXPOSE 8080

COPY ${JAR_LOC}/${JAR_FILE} /${TARGET_JAR}
ADD http://download.newrelic.com/newrelic/java-agent/newrelic-agent/current/newrelic-java.zip /
RUN unzip /newrelic-java.zip \
 && rm /newrelic-java.zip \
 && mkdir /newrelic/logs

WORKDIR /
CMD ["/bin/sh", "-c", "java -jar ${TARGET_JAR}"]

