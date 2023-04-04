SHELL = /bin/bash


SASSFLAGS = --style nested -M

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
	sassc $(SASSFLAGS) css/lib/cosmo/cosmo.scss > css/lib/cosmo.css


# Watch changes in SASS files and compile the CSS bundle
bootswatch-watch:
	while true; do \
  		make bootswatch; \
  		inotifywait -qre close_write ./css/lib/cosmo/; \
	done



# esbuild - a very fast JavaScript bundler written in Go
install-esbuild:
	npm install --save-exact esbuild

# create bundle from the source
esbuild:
	./node_modules/.bin/esbuild modules/clean/_javascript/clean.js --bundle --sourcemap --target=es6 --outfile=modules/clean/assets/js/clean.js

esbuild-watch:
	./node_modules/.bin/esbuild modules/clean/_javascript/clean.js --bundle --sourcemap --outfile=modules/clean/assets/js/clean.js --watch
