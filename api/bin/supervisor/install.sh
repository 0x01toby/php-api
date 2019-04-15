#!/usr/bin/env bash

# sudo -H -u nobody bash -c "/usr/local/bin/php artisan queue:listen --sleep=0 --queue=lumen-queue --tries=1 --timeout=600 --memory=512"

sudo apt-get update && sudo apt-get install supervisor && sudo systemctl enable supervisor
sudo cp queue.conf /etc/supervisor/conf.d/
