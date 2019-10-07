## Container Management

We have built the image in the previous section, lets now see how we can run a container using that image.

### Run a container
```bash
docker run [OPTIONS] IMAGE [COMMAND] [ARG...]
```

#### Running container in iteractive mode with STDOUT on the screen
```bash
docker run -it helloworld:1.0.0
```
Enables the app to run in foreground.

#### Running container in iteractive mode with shell login
```bash
docker run -it helloworld:1.0.0 /bin/sh
```
Enables the User to jump into the Container shell

#### Running container in detached mode
```bash
docker run -d helloworld:1.0.0
```
Enables the app in background.

#### Running container with port exposed
```bash
docker run -d -p 8080:8080 helloworld:1.0.0
```
Enables the app to be accessed at localhost. `docker ps` will print the port thats exposed.

#### Running container with env values passed
```bash
docker run -d -p 8081:8080 -e test=demo helloworld:1.0.0
```
Run `docker inspect CONTAINER` to check the ENV value passed.

#### Running container with a specific name
Did you notice the weird container names when you do `docker ps -a`.

To make this meaningful, lets name the container. You can do this by two ways:

```bash
docker run -d --name helloworld -p 8082:8080 helloworld:1.0.0
```
(or)
```bash
docker rename CONTAINER NEW_NAME
```

Let's see what happens if we try to run the container with the same name when one already exists
```bash
docker run -d --name helloworld -p 8083:8080 helloworld:1.0.0
docker: Error response from daemon: Conflict. The container name "/helloworld" is already in use by container "ceab1c62a3e30f2b05adce31b4b9a65ec143102596082d6f80fd6e9f97dbf624". You have to remove (or rename) that container to be able to reuse that name.
See 'docker run --help'.
```

We need to stop & remove the old container, so we can build a new one. Also try to run `docker ps -a`

```bash
docker ps -a | grep helloworld
ceab1c62a3e3        helloworld:1.0.0                 "/bin/sh -c 'java -j…"   3 minutes ago       Up 3 minutes                  0.0.0.0:8082->8080/tcp   helloworld
8e19c4229c14        helloworld:1.0.0                 "/bin/sh -c 'java -j…"   7 minutes ago       Up 7 minutes                  0.0.0.0:8081->8080/tcp   quirky_raman
9e41b08b869e        helloworld:1.0.0                 "/bin/sh -c 'java -j…"   11 minutes ago      Created                                                condescending_archimedes
528fdadfdfc7        helloworld:1.0.0                 "/bin/sh -c 'java -j…"   16 minutes ago      Up 16 minutes                 0.0.0.0:8080->8080/tcp   vigorous_mirzakhani
dc409cb2d531        helloworld:1.0.0                 "/bin/sh -c 'java -j…"   18 minutes ago      Up 18 minutes                 8080/tcp                 inspiring_dewdney
8846816b040f        helloworld:1.0.0                 "/bin/sh"                19 minutes ago      Exited (1) 18 minutes ago                              friendly_thompson
c334129def9c        helloworld:1.0.0                 "/bin/sh -c 'java -j…"   21 minutes ago      Exited (130) 19 minutes ago                            vigilant_panini
```

Oh too many old containers, time for cleanup.

### Stop containers
To stop container/(s), find the container id/name and list then with a space between them
```bash
docker stop [OPTIONS] CONTAINER [CONTAINER...]
```

**Tip**: To stop multiple containers based on the Image it uses:
```bash
docker ps -a -f ancestor="helloworld:1.0.0" -q | xargs docker stop 
```

### Start containers
You guessed it right, to start run:
```bash
docker start [OPTIONS] CONTAINER [CONTAINER...]
```

### Restart containers
Same applies to restarting container:
```bash
docker restart [OPTIONS] CONTAINER [CONTAINER...]
```

### Removing containers
What about deleting container. You first need to stop the container before removing. Lets try this:
```bash
docker rm helloworld
Error response from daemon: You cannot remove a running container dfddee42418f3e4ca029109d2bd188de8aa8c4fd0de3342f238c6af0104e9045. Stop the container before attempting removal or force remove
```

Correct way is to gracefully stop the Container and then remove it using `docker rm`.
If you in a hurry and need to force remove it:
```bash
docker rm -f helloworld
helloworld
```

#### Did you know
You cannot delete an image when a running container is using it.

```bash
docker rmi a6bf8d25915e
Error response from daemon: conflict: unable to delete a6bf8d25915e (cannot be forced) - image is being used by running container b0cc11d4dac9
```

### Jump into a running container
`docker exec` is used to jump into the Container shell for troubleshooting.

```bash
docker exec -it helloworld /bin/sh
```

* -u - is to used to jump as specific user
* -e - is to pass env values while exec'ing
* -it - for interactive terminal

In this chapter we have covered about how to run/stop/start/restart/remove containers. Next one to address is "data persistence"

[Lab 5 - Docker Volume](../docker-volume-lab5/README.md)
