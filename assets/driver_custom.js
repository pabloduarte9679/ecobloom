// driver_custom.js
// Define funciones para el tour y el texto explicativo para \"nuevos\"

function startEcoTour(){
  const driver = new Driver({
    allowClose: true,
    overlayClickNext: true
  });

  driver.defineSteps([
    {
      element: '#tutorial',
      popover: { title: 'Bienvenido a EcoBloom', description: 'EcoBloom es un mini-invernadero automatizado. Te mostraremos lo básico.', position: 'bottom' }
    },
    {
      element: '#estado',
      popover: { title: 'Estado actual', description: 'Aquí verás temperatura y humedad en tiempo real.', position: 'top' }
    },
    {
      element: '#experto',
      popover: { title: 'Panel de control', description: 'En el panel de control se enciende/apaga la luz y se piden lecturas.', position: 'bottom' }
    }
  ]);

  const guide = "Cuidados básicos:\\n\\n1) Mantén humedad adecuada (40-70%).\\n2) Evita luz directa muy intensa si son plántulas.\\n3) Revisa sustrato y sistema de riego.\\n4) Ventila periódicamente para evitar hongos.\\n5) Monitorea temperatura y ajusta según especie.";
  document.getElementById('guide-text').innerText = guide;

  driver.start();
}

// export
window.startEcoTour = startEcoTour;