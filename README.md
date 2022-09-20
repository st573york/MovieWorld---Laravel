<h1 align="center">Movie World</h1>

## How to run the application on macOS

Pull image from docker hub:

docker pull st573york/movieworld
Run image in a new container:

docker run -itd --privileged -p 80:80 st573york/movieworld:latest /usr/sbin/init
Browse to localhost

## Task
