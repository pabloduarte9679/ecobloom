<?php
// index.php - EcoBloom
// Requiere PHP 7+, servidor web y que luz.php y api.php estén accesibles
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>EcoBloom — Mini Invernadero Inteligente</title>
  <link rel="stylesheet" href="/assets/styles.css">
  <!-- driver.js (tour) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css">
  <script src="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.js"></script>
</head>
<body>
  <header class="site-header">EcoBloom — Mini Invernadero Inteligente</header>
  <main class="container">

    <!-- Modal / pregunta inicial simple -->
    <section class="card" id="pregunta">
      <h2>¿Eres agricultor o eres nuevo en el cultivo?</h2>
      <div class="row">
        <button class="btn" id="btn-experto">Agricultor</button>
        <button class="btn" id="btn-nuevo">Soy nuevo</button>
      </div>
    </section>

    <!-- Panel experto -->
    <section class="card" id="experto" style="display:none;">
      <h2>Panel de Control</h2>
      <p>Controles rápidos para pruebas y lectura de sensores.</p>
      <div class="row">
        <button class="btn" id="luz-on">Encender luz</button>
        <button class="btn" id="luz-off">Apagar luz</button>
        <button class="btn" id="leer-sensores">Leer sensores</button>
      </div>
      <pre id="respuesta"></pre>
    </section>

    <!-- Tutorial para nuevos con botón para iniciar Driver.js -->
    <section class="card" id="tutorial" style="display:none;">
      <h2>Guía rápida del invernadero</h2>
      <p>Si eres nuevo, sigue este recorrido interactivo que explica los cuidados básicos.</p>
      <button class="btn" id="start-tour">Iniciar Guía Interactiva</button>
      <div id="guide-text" style="margin-top:12px"></div>
    </section>

    <!-- Sección estado -->
    <section class="card" id="estado">
      <h2>Estado actual</h2>
      <ul id="estado-list">
        <li>Temperatura: <span id="temp">—</span> ºC</li>
        <li>Humedad: <span id="hum">—</span> %</li>
        <li>Última lectura: <span id="last">—</span></li>
      </ul>
    </section>

  </main>

  <script src="/assets/driver_custom.js"></script>
  <script>
    // Manejo simple de UI y llamadas a PHP
    document.getElementById('btn-experto').onclick = () => {
      document.getElementById('pregunta').style.display = 'none';
      document.getElementById('experto').style.display = 'block';
      fetchEstado();
    }

    document.getElementById('btn-nuevo').onclick = () => {
      document.getElementById('pregunta').style.display = 'none';
      document.getElementById('tutorial').style.display = 'block';
    }

    document.getElementById('luz-on').onclick = () => controlLuz('on');
    document.getElementById('luz-off').onclick = () => controlLuz('off');
    document.getElementById('leer-sensores').onclick = fetchEstado;

    document.getElementById('start-tour').onclick = () => iniciarTour();

    function controlLuz(estado){
      fetch('luz.php?estado=' + estado)
        .then(r => r.text())
        .then(t => document.getElementById('respuesta').innerText = t)
        .catch(e => document.getElementById('respuesta').innerText = 'Error: ' + e);
    }

    function fetchEstado(){
      fetch('api.php?action=read')
      .then(r => r.json())
      .then(d => {
        document.getElementById('temp').innerText = d.temperature;
        document.getElementById('hum').innerText = d.humidity;
        // timestamp a fecha legible
        const t = new Date(d.timestamp*1000);
        document.getElementById('last').innerText = t.toLocaleString();
      })
      .catch(e => console.error(e));
    }

    // Tour: se implementa en /assets/driver_custom.js
    function iniciarTour(){
      if (window.startEcoTour) window.startEcoTour();
    }

    // Auto-refresh cada 30s
    setInterval(fetchEstado, 30000);
  </script>
</body>
</html>
