#!/bin/bash

a=`pgrep php`
if [ $a ]; then
    sudo pkill php &> /dev/shm/null
    echo "Server restarted"
    cd ..
    php -S localhost:8080 -t public &> /dev/shm/null
else
    echo "Server Started"
    cd ..
    php -S localhost:8080 -t public &> /dev/shm/null
fi
