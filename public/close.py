#!/usr/bin/env python3

import RPi.GPIO as GPIO
import time

GPIO.setmode(GPIO.BCM)

out_pin = 4
GPIO.setup(out_pin, GPIO.OUT)

servo = GPIO.PWM(out_pin, 50)

servo.start(0.0)


#servo.ChangeDutyCycle(2.5)
#time.sleep(0.5)

#servo.ChangeDutyCycle(7.25)
#time.sleep(0.5)

servo.ChangeDutyCycle(12)
time.sleep(0.5)

servo.stop()
GPIO.cleanup()
