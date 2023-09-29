SHELL = /bin/bash


# SASSFLAGS = --style nested -M
SASSFLAGS = --style compressed -M

# sassc - a very fast SASS compiler written in C++
#install-sass:
#	sudo apt install sassc

bootswatch:
	sassc $(SASSFLAGS) scss/lib/lux/lux.scss > ./../decimus_child/css/lib/lux.css

woo:
	sassc $(SASSFLAGS) woocommerce/scss/woocommerce-style2.scss > woocommerce/css/woocommerce-style2.css


# Watch changes in SASS files and compile the CSS bundle
bootswatch-watch:
	while true; do \
  		make bootswatch; \
  		inotifywait -qre close_write ./scss/lib/lux/; \
	done
