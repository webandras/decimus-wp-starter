# base image
FROM node:12.2.0-alpine

# set working directory
WORKDIR /usr/src/decimus-admin

# add to $PATH
ENV PATH /usr/src/decimus-admin/node_modules/.bin:$PATH

#RUN npm install
RUN npm install @vue/cli
COPY . /usr/src/decimus-admin

# start app
CMD ["npm", "run", "serve"]
