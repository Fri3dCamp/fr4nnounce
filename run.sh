docker stop fr4nnounce && docker rm fr4nnounce
docker build -t fr4nnounce:latest .
docker run -d --name fr4nnounce -p 80:80 fr4nnounce:latest