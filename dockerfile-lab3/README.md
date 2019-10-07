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

### Lets build our Image

Now that we have our Dockerfile ready, lets build and tag it for running as container.

To build an image, 
```bash
docker build [OPTIONS] PATH | URL | -
```

The `docker build` command builds Docker images from a Dockerfile and a “context”. A build’s context is the set of files located in the specified `PATH` or `URL`. For example, your build can use a COPY instruction to reference a file in the context.

The `--tag` passed during the `docker build` adds a tag to the image so that it gets a nice repository name and tag. Lets ignore the repository name for now and build an image.

```bash
docker build .

Sending build context to Docker daemon  31.94MB
Step 1/12 : FROM openjdk:8-jre-alpine
 ---> f7a292bbb70c
Step 2/12 : LABEL maintainer "Sathya Kumar"
 ---> Using cache
 ---> 0251c4a0f879
Step 3/12 : ARG JAR_FILE='hello-world-app-0.0.1-SNAPSHOT.jar'
 ---> Using cache
 ---> b58e44d148dc
Step 4/12 : ARG JAR_LOC='.'
 ---> Using cache
 ---> 4ad25df5ca7b
Step 5/12 : ARG TARGET_JAR='hello-world-app.jar'
 ---> Using cache
 ---> 2a8f14f55617
Step 6/12 : ENV TARGET_JAR=$TARGET_JAR
 ---> Using cache
 ---> e78926cc0d59
Step 7/12 : EXPOSE 8080
 ---> Using cache
 ---> 19a0bc5ec351
Step 8/12 : COPY ${JAR_LOC}/${JAR_FILE} /${TARGET_JAR}
 ---> Using cache
 ---> c83fe55b83f6
Step 9/12 : ADD http://download.newrelic.com/newrelic/java-agent/newrelic-agent/current/newrelic-java.zip /
Downloading [==================================================>]  11.08MB/11.08MB
 ---> Using cache
 ---> 2f1d517d1d20
Step 10/12 : RUN unzip /newrelic-java.zip  && rm /newrelic-java.zip  && mkdir /newrelic/logs
 ---> Using cache
 ---> 8dbb67581b74
Step 11/12 : WORKDIR /
 ---> Using cache
 ---> e8532e8bca44
Step 12/12 : CMD ["/bin/sh", "-c", "java -jar ${TARGET_JAR}"]
 ---> Using cache
 ---> 6c9381e17b7b
Successfully built 6c9381e17b7b
Successfully tagged 6c9381e17b7b:latest
```

Run `docker images` to list the images and find our image.

**Tip** : As the values in ENV & ARG get printed in the `docker history`, do not store any sensitive information in it.

### Tagging an image

Tagging an image is very important, as we mentioned about dangling images. It makes finding the images easy and also plays a vital role in pushing our images to the Docker repository.

Syntax:
```bash
docker tag SOURCE_IMAGE[:TAG] TARGET_IMAGE[:TAG]
```

You can also tag your image during the Image build 
```bash
docker build -t helloworld:1.0.0 .
```

Now we have the Image built and tagged, it is time to push this into a Docker repository. 


### Push Image to Docker repository
Run `docker info` to find out which docker respository you have signed in to.
If you have not logged into any, run `docker login` which default to hub.docker.com

To push the image to Docker repository:
```bash
docker push [OPTIONS] NAME[:TAG]
```

Lets push our helloworld image:
```bash
docker push helloworld:1.0.0
The push refers to repository [docker.io/library/helloworld]
f785b09dd0d2: Preparing
04b588f1c6e1: Preparing
954271ee1fd9: Preparing
edd61588d126: Preparing
9b9b7f3d56a0: Preparing
f1b5933fe4b5: Waiting
denied: requested access to the resource is denied
```

OOPS ! what happened there. Because the above image did not represent any repository in the Docker hub. Find your repository name from docker hub and tag your image based on this.

```bash
docker tag helloworld:1.0.0 kumarsathya/helloworld:1.0.0
docker push kumarsathya/helloworld:1.0.0

The push refers to repository [docker.io/kumarsathya/helloworld]
f785b09dd0d2: Pushed
04b588f1c6e1: Pushed
954271ee1fd9: Pushed
edd61588d126: Pushed
9b9b7f3d56a0: Pushed
f1b5933fe4b5: Pushed
1.0.0: digest: sha256:f8429ad3c51f5a73ac1af7f168d3363cbb72a3abdc72c3db1ed6dab8ea6fa775 size: 1583
```

**Tip**: To pull an image from Docker hub which is publicly available, there is no login needed.

In this page, we have covered what is Dockerfile, how to write one, how to build it, tag it and push it to repository. Next step is how to run Containers out of it.

[Lab 4 - Container management](../docker-containers-lab4/README.md)