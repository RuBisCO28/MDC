# Manage Devices Checkout

Use this application to manage devices checkout. This application uses the latest Slim 3 with the PHP-View template renderer and Python code.

This application consists of two parts.
1. Record Devices Checkout/Return and Lock/Unlock shelf's door using by USB camera
2. Monitor device status with browser

# Tools
```
Rasberry Pi 3 MODEL B (OS Rasbian)
USB camera
QR code
micro servo SG92R(http://akizukidenshi.com/catalog/g/gM-08914/)
Shelf to storage devices
```

# Run Application

1. Run gui.py under public directory  
```
$ cd public
$ python3 gui.py
```

2. Launch Server to monitor Checkout/Return Devices with browser
```
# Push "Launch Server" button, then Server will be started
# Push "Open Browser" button, then Browser will be opened
# Push "QR reader" button, then QR Reader will be launched
```

# Gallery

![qr](https://user-images.githubusercontent.com/54470624/64060375-b2043100-cc06-11e9-9267-ae9bd3b266cb.png)

![slim9](https://user-images.githubusercontent.com/54470624/64060393-d9f39480-cc06-11e9-8b92-aa5a0ec6d351.png)

![servo](https://user-images.githubusercontent.com/54470624/64060400-f7286300-cc06-11e9-8ffb-a19b143243b3.png)

