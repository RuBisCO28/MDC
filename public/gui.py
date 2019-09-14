#!/usr/bin/env python3

import tkinter as tk
import os
import sys
import time
import subprocess

root = tk.Tk()
root.title("MDC")
root.geometry("200x200")

def LaunchServer(event):
    ls = ["bash","ls.bash"]
    subprocess.Popen(ls)

Button = tk.Button(text='Launch Server', width=15)
Button.bind("<Button-1>",LaunchServer) 
Button.place(x=25,y=25)

def OpenBrowser(event):
    ob = ["bash","ob.bash"]
    subprocess.Popen(ob)

Button = tk.Button(text='Open Browser', width=15)
Button.bind("<Button-1>",OpenBrowser) 
Button.place(x=25,y=75)

def QRReader(event):
    qr = ["python3","device_manage.py"]
    subprocess.Popen(qr)

Button = tk.Button(text='QR Reader', width=15)
Button.bind("<Button-1>",QRReader) 
Button.place(x=25,y=125)

root.mainloop()

