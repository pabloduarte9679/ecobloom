<?php

$udp = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($udp, "0.0.0.0", 5005);

socket_recvfrom($udp, $buf, 1024, 0, $from, $port);
socket_close($udp);

if (strpos($buf, "PICO_IP:") === 0) {
    $pico_ip = trim(substr($buf, 8));
} else {
    die("Invalid discovery packet received");
}

echo "rpi at: $pico_ip\n";

$tcp = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_connect($tcp, $pico_ip, 8080);

$estado = $_GET['estado'] ?? '';

if ($estado == 'on') socket_write($tcp, "1");
if ($estado == 'off') socket_write($tcp, "0");

socket_close($tcp);
?>

