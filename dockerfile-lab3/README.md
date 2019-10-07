## Dockerfile
Docker image is built from a Dockerfile. Dockerfile is a text file with a set of instructions, which tells Docker on how to create the Image. 

Consider `Dockerfile` is a recipe for baking a cake. The recipe will include all the ingredients needed to bake a cake. The sequence is very important. You cannot bake the cake without mixing the proper ingredients. You cannot ice the cake before its baked and cold. Same applies to `Dockerfile`. The order is important, you cannot build your app without the foundation installed. 


<img src="https://cdn.pixabay.com/photo/2014/04/03/10/24/birthday-cake-310370_960_720.png" alt="layers" width="200"/>

### Writing first Dockerfile

```bash
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

```

* FROM - Defines the base image
* LABEL - mentions about the mantainer of the Image
* ARG - Defines variables that can be used during Image build time
* ENV - Defines env variables where the value persists in the container during run-time. Point to note that ENV variable will override the value in ARG if its with the same name.
* EXPOSE - Informs Docker that the container listens on the specified network port at runtime
* COPY - To copy data from local to the container build process while building the image.
* ADD - To copy/download files/directories from both local as well remote URLs to the build container.
* RUN - To run commands during the Image build process
* WORKDIR - Sets the working directory for any RUN, CMD, ENTRYPOINT, COPY, and ADD instructions that follow it
* CMD - This is more like the command to run when the container comes up. This is default one to run unless something passed as part of `docker run`.
  
To understand each commands in depth, would recommend to refer it [here](https://kapeli.com/cheat_sheets/Dockerfile.docset/Contents/Resources/Documents/index).

If you would like to do some linting on your Dockerfile, check [hadolint](https://github.com/hadolint/hadolint).