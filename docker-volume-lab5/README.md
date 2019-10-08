## Docker Storage

All containers should be ephemeral as possible. By “ephemeral”, we mean that the container can be stopped and destroyed, then rebuilt and replaced with an absolute minimum set up and configuration. As 12-factors say, applications should be stateless. What this means is, the data stored in the Container will be removed once the container is removed.

Give a try by creating a container from the `helloworld` image, touch a file and remove the container.
Again recreate a new container from the same image and see if you are able to find the file you touched. You will not find that file.

What if your application needs to persist the data outside of the container? You got two options.[tmpfs is just a storage option]

<image src="https://docs.docker.com/storage/images/types-of-mounts-volume.png" alt="volumes">

* **Volumes** are stored in a part of the host filesystem which is managed by Docker (`/var/lib/docker/volumes/` on Linux). Non-Docker processes should not modify this part of the filesystem. Volumes are the best way to persist data in Docker.

* **Bind mounts** may be stored anywhere on the host system. They may even be important system files or directories. Non-Docker processes on the Docker host or a Docker container can modify them at any time.

* **tmpfs** mounts are stored in the host system’s memory only, and are never written to the host system’s filesystem.

## Docker volume
This is the most preferrable option for Data persistence in Docker.

#### To create volume
```bash
docker volume create my-vol
```

#### To list volumes
```bash
docker volume ls
```

#### To remove volume
```bash
docker volume rm my-vol
```
Removing a container will not remove the volume associated with it, as expected.

#### To mount a volume to a container
```bash
docker run -d --name helloworld -v myvol:/app helloworld:1.0.0
```  

`docker inspect helloworld` will provide information on the mount

**Tip**: To make the volume readonly `-v myvol:/app:ro`


### Time to test the volume. 

Lets create a volume and share it between two containers to see whether the data is persisted and shared.

**Creating the volume**:
```bash
docker volume create my-vol
```

**Creating first container with above volume mounted**:
```bash
docker run -d --name helloworld1 -p 8083:8080 -v my-vol:/app helloworld:1.0.0
```

**Exec & Write some data into the volume from Container1**:
```bash
touch /app/from-container1.txt
```

**Creating second container with above volume mounted**:
```bash
docker run -d --name helloworld2 -p 8084:8080 -v my-vol:/app helloworld:1.0.0
```

**Exec & Check if data from Container1 persisted in the volume**:
```bash
docker exec -it helloworld2 /bin/sh
ls -lrt /app
```

You should see the data written by container1 under `/app`. Perfect ! Data is persisted.


**Tip**: To remove all unused volumes and free up space:
```bash
docker volume prune
```

### Did you know:
That you can mount volumes from running container to another new container?
```bash
docker run -d --name helloworld3 -p 8084:8080 --volumes-from helloworld helloworld:1.0.0

docker exec -it helloworld3 /bin/sh
/ # ls -lrt /app
total 0
-rw-r--r--    1 root     root             0 Oct  7 23:39 from_container1.txt
```

## Bind-mounts
Bind mounts have been around since the early days of Docker. Bind mounts have limited functionality compared to volumes. When you use a bind mount, a file or directory on the host machine is mounted into a container. The file or directory is referenced by its full or relative path on the host machine.

Lets test Bind mount by mounting a file from local into the container:

#### Creating tmp directory and files in local:
```bash
mkdir /tmp/test-bind-mount; touch /tmp/test-bind-mount/test1.txt; touch /tmp/test-bind-mount/test2.txt
```

#### Creating container with bind-mount:
```bash
docker run -d --name helloworld1 -p 8080:8080 -v /tmp/test-bind-mount:/test-bind-mount helloworld:1.0.0

docker exec -it helloworld1 /bin/sh
/ # ls -lrt test-bind-mount/
total 0
-rw-r--r--    1 root     root             0 Oct  8 00:07 test2.txt
-rw-r--r--    1 root     root             0 Oct  8 00:07 test1.txt
```

The files are present in the container as expected. Lets push some data from the container mount and see if the files reflect in the local
```bash
#from container
/test-bind-mount # touch from-container.txt
/test-bind-mount # ls -rtla
total 4
-rw-r--r--    1 root     root             0 Oct  8 00:07 test2.txt
-rw-r--r--    1 root     root             0 Oct  8 00:07 test1.txt
drwxr-xr-x    1 root     root          4096 Oct  8 00:07 ..
-rw-r--r--    1 root     root             0 Oct  8 00:16 from-container.txt
drwxr-xr-x    5 root     root           160 Oct  8 00:16 .

#from local
╰─ ls -lrt /tmp/test-bind-mount
  rw-r--r--  427823  wheel     0 B    Mon Oct  7 20:07:03 2019  test1.txt
  rw-r--r--  427823  wheel     0 B    Mon Oct  7 20:07:03 2019  test2.txt
  rw-r--r--  427823  wheel     0 B    Mon Oct  7 20:16:09 2019  from-container.txt
```

## tmpfs
As opposed to `volumes` and `bind mounts`, a `tmpfs` mount is temporary, and only persisted in the host memory. When the container stops, the `tmpfs` mount is removed, and files written there won’t be persisted. We are not going to cover it as we are interested in Data persistence.

### Did you know
To copy the data from local to the container or from container to the local:
```bash
Usage:	docker cp [OPTIONS] CONTAINER:SRC_PATH DEST_PATH|-
	    docker cp [OPTIONS] SRC_PATH|- CONTAINER:DEST_PATH
```

In this chapter, we have learned about Docker storage options to persist the data. Next up is docker-compose.

[Lab 6 - Docker compose](../docker-compose-lab6/README.md)