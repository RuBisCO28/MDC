#!/bin/bash

pkill php
cd ..
php -S localhost:8080 -t public &> /dev/shm/null
firefox http://localhost:8080/devices

