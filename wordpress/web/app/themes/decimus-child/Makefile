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
  		make; \
  		inotifywait -qre close_write ./scss; \
	done

bootswatch:
	sassc $(SASSFLAGS) ./../decimus/css/lib/cosmo/cosmo.scss > css/lib/cosmo.css


# Watch changes in SASS files and compile the CSS bundle
bootswatch-watch:
	while true; do \
  		make bootswatch; \
  		inotifywait -qre close_write ./../decimus/css/lib/cosmo/; \
	done
