## Docker compose
Compose is a tool for defining and running multi-container Docker applications. With Compose, you use a YAML file to configure your application’s services. Then, with a single command, you create and start all the services from your configuration. All application's configs are maintained in one file called `docker-compose.yml`. This is present in the root directory.

### Writing our docker-compose.yml
Lets build a website which is frontend'ed with Apache and MYSQL as backend that prints User information and takes User input too. Check the code for the same in `docker-compose-lab6` folder.

```
version: '3'

services:
  apache:
    build: apache
    ports:
      - 8080:80
    links:
      - mysql

  mysql:
    build: mysql
    environment:
      - MYSQL_ROOT_PASSWORD=test
      - MYSQL_DATABASE=docker_in_motion
    volumes:
      - db-data:/var/lib/mysql

volumes:
  db-data:
```

The structure is important as it pulls the Dockerfile from the specific build context location.

### To build & run the Containers using docker-compose
```bash
docker-compose up -d

#check images created
docker images

#check containers running
docker ps 

#check network created
docker network ls

#check volume created
docker volume ls
```

So, its a single place to manage all of the entities needed to run the multi-container architecture.

Open browser and hit http://localhost:8080/ to view the contents which made it possible from Apache interacting with the Mysql database.

### To update images
`docker-compose build` will update & recreate both the images if there are changes, in a single command.


### To delete all the Services & Containers
```bash
docker-compose down
```
This will delete networks, containers created, but will leave the Images & Volumes untouched for reuse and data persistence. 

## References
Special thanks to the online learning video from [Docker in Motion by Peter Fisher](https://learning.oreilly.com/videos/docker-in-motion/10000MNLV201711). 

<img src="https://i.pinimg.com/originals/0f/23/5e/0f235e777748deb8a3509cedb28cd2bf.jpg" alt="end" width=600/>