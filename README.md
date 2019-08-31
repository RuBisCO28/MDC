# Monitor Checkout/Return Devices Application

Use this application to monitor checkout/return Devices.This application uses the latest Slim 3 with the PHP-View template renderer and Python code.

This application consists of two parts.
1. Record Checkout/Return Devices with camera
2. Monitor device status with browser

# Run Application

1. Run device_manage.py under public directory
```
$ cd public
$ python3 device_manage.py
```

2. Run build in server
```
$ ./setup.bash
```

3. Monitor Checkout/Return Devices with browser
```
# Access the following link with browser
localhost:8080/devices
```
