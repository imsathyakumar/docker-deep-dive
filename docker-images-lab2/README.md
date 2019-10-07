# Docker images

Docker containers are built from Docker images. Images are the building blocks, the structural foundation of Containers. 

## How Docker images are built

Docker images are built as layers, like one on top of another. Each layer is built as part of a container. 


### To list the images

```bash
docker images
```

### To list the containers 

```bash
docker ps -a
```

### To pull an image from the Docker repository

```bash
Usage:	docker pull [OPTIONS] NAME[:TAG|@DIGEST]
```

### Lets pull nginx image to your local

```bash
docker pull nginx
```
by default it pulls the latest tag, if none specified. To pull a specify tag, pass the tag as below:

```bash
docker pull nginx:1.16.1
```

Run `docker images` to view both the images exist on your local.

To understand the layers involved in the `nginx` image use history,

```bash
docker history nginx
```

To understand the detailed information about an image, `inspect` is your friend,

```bash
docker inspect nginx
```

Tip on inspect : `--format="{{ .Id }}"`


### Lets remove the Image from local

Usage: 
```bash
docker rmi [OPTIONS] IMAGE [IMAGE...]
```

```bash
docker rmi nginx:latest
Error response from daemon: conflict: unable to remove repository reference "nginx" (must force) - container 6a18f5421405 is using its referenced image f949e7d76d63
```

Since the container is attached to this Image, we are unable to remove the Image. Use `-f` in rmi to force remove the image.

### To remove unused images

```bash
docker image prune --help

Usage:	docker image prune [OPTIONS]

Remove unused images

Options:
  -a, --all             Remove all unused images, not just dangling ones
      --filter filter   Provide filter values (e.g. 'until=<timestamp>')
  -f, --force           Do not prompt for confirmation
```

### What is dangling image

Docker images consist of multiple layers. Dangling images are layers that have no relationship to any tagged images. They no longer serve a purpose and consume disk space. They can be located by adding the filter flag, `-f` with a value of `dangling=true` to the `docker images` command. When you’re sure you want to delete them, you can use the `docker image prune`

**Note**: *If you build an image without tagging it, the image will appear on the list of dangling images because it has no association with a tagged image.*

So far we have pulled down the publicly available image and worked on it. What if we need to customize it for our needs. We can achieve this with `Dockerfile.`

[Lab 3 - Dockerfile](../dockerfile-lab3/README.md)