SHELL = /bin/bash


# SASSFLAGS = --style nested -M
SASSFLAGS = --style compressed -M

# sassc - a very fast SASS compiler written in C++
#install-sass:
#	sudo apt install sassc

woo:
	sassc $(SASSFLAGS) woocommerce/scss/woocommerce-style.scss > woocommerce/css/woocommerce-style.css


# Watch changes in SASS files and compile the CSS bundle
woo-watch:
	while true; do \
  		make woo; \
  		inotifywait -qre close_write ./woocommerce/scss/; \
	done
