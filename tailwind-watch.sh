#!/bin/bash

docker run --rm -t --init --interactive --volume $PWD:/root --workdir /root node npx tailwindcss -i ./css/style.css -o ./html/css/style.css --watch
