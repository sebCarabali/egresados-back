FROM nginx:alpine
COPY . /application
COPY site.conf /etc/nginx/conf.d/default.conf

RUN apt-get update
RUN apt-get install -y \
        cron

# Create the log file
RUN touch /var/log/schedule.log
RUN chmod 0777 /var/log/schedule.log

# Add crontab file in the cron directory
ADD cron/scheduler /etc/cron.d/scheduler

# Run the cron
# RUN cron/scheduler /etc/cron.d/scheduler
#CMD printenv > /etc/environment && echo “cron starting…” && (cron) && : > /var/log/schedule.log && tail -f /var/log/schedule.log
CMD cron && tail -f /var/log/schedule.log
# CMD ["cron", "-f"]


