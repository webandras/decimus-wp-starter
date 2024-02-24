SHELL = /bin/bash


# SASSFLAGS = --style nested -M
SASSFLAGS = --style compressed -M

# sassc - a very fast SASS compiler written in C++
#install-sass:
#	sudo apt install sassc

sass:
	sassc $(SASSFLAGS) scss/main.scss > css/main.css


# Watch changes in SASS files and compile the CSS bundle
sass-watch:
	while true; do \
  		make bootswatch; \
  		inotifywait -qre close_write ./scss; \
	done

bootswatch:
	sassc $(SASSFLAGS) ./scss/main.scss > css/main.css
