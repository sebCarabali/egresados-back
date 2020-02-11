FROM nginx:alpine
COPY . /application
COPY site.conf /etc/nginx/conf.d/default.conf
