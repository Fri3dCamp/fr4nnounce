# Fr4nnounce

Fri3d announcement screens.

Twig based, with some session wrangling to show upcoming sessions based on Pretalx json export.

## How to make it go

- `run.sh` starts a docker container, making the schedule available at localhost (port 80)
- `tailwind-watch.sh` Monitors all the twig files in `./templates` and generates the css file `html/css/style.css`, based on `tailwind.config.js` and `./css/style.css`.
- `composer.sh` is basically dockerized composer. Just `bash composer.sh install` to install whatever's in `composer.json` and otherwise use like you would use composer.