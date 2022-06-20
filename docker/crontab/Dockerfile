FROM austinkregel/base:latest

RUN apt-get update \
    && apt-get install -y cron

COPY basecron /etc/cron.d/basecron
# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/basecron
# Apply cron job
RUN crontab /etc/cron.d/basecron
# Create the log file to be able to run tail
RUN touch /var/log/cron.log
# Run the command on container startup
CMD cron && tail -f /var/log/cron.log
