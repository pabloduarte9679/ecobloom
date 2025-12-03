import network
import time
import socket
import sys
import _thread
from machine import Pin

with open("wifi.txt") as f:
            ssid = f.readline().strip()
            passwd = f.readline().strip()
print(ssid)
AP_SSID = "invernadero"
AP_PASSWORD = "12345678"

def start_ap():
    ap = network.WLAN(network.AP_IF)
    ap.config(essid=AP_SSID, password=AP_PASSWORD)
    ap.active(True)

    while not ap.active():
        time.sleep(1)

    print("AP: ", ap.ifconfig())
    return ap

 
html = """<!DOCTYPE html>
<html>
<head>
<title>Pico WiFi Setup</title>
</head>
<body>
<h2>Connect your Pico W to WiFi</h2>
<form method="POST">
<label>WiFi SSID:</label>
<input name="ssid" />
<label>Password:</label>
<input name="password" type="password" />
<button type="submit">Save & Connect</button>
</form>
</body>
</html>
"""


def start_web_server():
    addr = socket.getaddrinfo("0.0.0.0", 80)[0][-1]
    s = socket.socket()
    s.bind(addr)
    s.listen(1)
    print("server: 192.168.4.1")

    while True:
        client, _ = s.accept()
        request = client.recv(1024).decode()

        if "POST" in request:
            ssid = request.split("ssid=")[1].split("&")[0]
            password = request.split("password=")[1].split(" ")[0]

            ssid = ssid.replace("+", " ")
            password = password.replace("+", " ")

            with open("wifi.txt", "w") as f:
                f.write(ssid + "\n" + password)

            client.send("HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n")
            client.send("<h3>saved</h3>")
            client.close()
            time.sleep(1)
            machine.reset()

        else:
            client.send("HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n")
            client.send(html)
            client.close()


if ssid == "":
    start_ap()
    start_web_server()
    
led = Pin("LED", Pin.OUT)

def broadcast_ip():
    udp = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    udp.setsockopt(socket.SOL_SOCKET, socket.SO_BROADCAST, 1)

    ip = network.WLAN(network.STA_IF).ifconfig()[0]
    msg = f"PICO_IP:{ip}".encode()

    while True:
        udp.sendto(msg, ("255.255.255.255", 5005))
        time.sleep(0.2)

_thread.start_new_thread(broadcast_ip, ())
def connect():
    wlan = network.WLAN(network.STA_IF)
    wlan.active(True)
    wlan.connect(ssid, passwd)
    while not wlan.isconnected():
        print("Waiting for connection...")
        time.sleep(0.2)
    print(wlan.ifconfig()) 


connect()
sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server_address = ("0.0.0.0", 8080)
sock.bind(server_address)
sock.listen(1)
data = -1
while True:
    print("waiting for connection to server ...")
    connection, client_address = sock.accept()
    
    try:
        print("connection from", client_address)
        
        if True:
            data = connection.recv(4)
            print(int(data))
            if(int(data) == 1):
                led.on()
                time.sleep(1)
            elif(int(data) == 0):
                led.off()
                time.sleep(1)
        
    
    except KeyboardInterrupt:
        led.off()
        connection.close()
    finally:
        connection.close()

