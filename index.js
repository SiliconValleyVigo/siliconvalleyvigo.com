//ANIMACION SLIDER
const delay = (ms) => new Promise((res) => setTimeout(res, ms));

const tiempoVideo1 = 15000;
const tiempoVideo2 = 10000;
const tiempoVideo3 = 5000;

sliderAnimation();
setInterval("sliderAnimation()", tiempoVideo1 + tiempoVideo2 + tiempoVideo3);

async function sliderAnimation() {
  await delay(tiempoVideo1);
  moverAlPase(1);

  await delay(tiempoVideo2);
  moverAlPase(2);

  await delay(tiempoVideo3);
  moverAlPase(0);
}

function moverAlPase(pase) {
  let container = document.getElementById("containerPases");
  let video = document.getElementById("video" + pase);

  let translate = (100 / 3) * pase;

  container.style.transform = "translateX(-" + translate + "%)";
}


//CAMBIAR TAMAÑO VIDEO 1 CUADRO DE MANDO
window.addEventListener("resize", tamañoPase);

function tamañoPase() {
  if (document.getElementById('paginaInicio')) {
    let fondoImagenPase1 = document.getElementById('fondoImagenPase1');
    let video = document.getElementById("video0");

    let alto = fondoImagenPase1.offsetHeight;
    let ancho = fondoImagenPase1.offsetWidth;

    let ratio = ancho - alto;

    if (ratio < 0) {
      video.style.width = '100%';
      video.style.top = '1%';
      video.style.scale = '0.95';
      video.style.objectPosition = 'top center';
    } else {
      video.style.top = '4%';
      video.style.width = (alto / ancho) * 107 + '%';
      video.style.height = '100%';
      video.style.objectPosition = 'top center';
    }
  }
}

//FORMULARIO DE CONTACTO
function submitFormulario() {
  const form = document.querySelector("#contact-form");
  const resultDiv = document.querySelector("#result");

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    resultDiv.innerHTML = "";

    const formData = new FormData(form);
    const remitente = formData.get("remitente");
    const asunto = formData.get("asunto");
    const mensaje = formData.get("mensaje");

    fetch("/mail.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `remitente=${remitente}&asunto=${asunto}&mensaje=${mensaje}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          resultDiv.innerHTML = "<p>Mensaje enviado correctamente</p>";
        } else {
          resultDiv.innerHTML = '<p class="error">Error al enviar el mensaje</p>';
        }
      });
  });
}


//ANIMACION FORMULARIO
function focusInput(input, formulario) {
  let campo = document.getElementById(input);
  campo.style.backgroundColor = "rgb(24, 24, 24)";

  let letras = document.getElementById(formulario);
  letras.style.top = "-1rem";
  letras.style.fontSize = "1rem";
  letras.style.fontStyle = "italic";
}

/////////////NAVEGACIÓN////////////
// Obtener el path de la URL actual
const pathActual = window.location.pathname;

// Verificar si el path coincide con un valor específico
if (pathActual === '/ruta-especifica') { mostrarPagina(); }

function abrirMenu() {
  const menu = document.getElementById('menu');
  if (menu.style.scale == 0) {
    menu.style.scale = 1;
  } else {
    cerrarMenu()
  }
}

function cerrarMenu() {
  const menu = document.getElementById('menu');
  menu.style.scale = 0;
}

function cambiarURL(nuevaParte) {
  let url = new URL(window.location.href);
  url.pathname = nuevaParte;
  let nuevaURL = url.href;

  window.history.pushState(null, null, nuevaURL);
}

function mostrarPagina(url, updateUrl = true) {
  let path = url;
  url = `/pages/${url}.html`;
  let id = 'main';

  //Cargar inicio solo si no está en inicio
  let inicio = document.getElementById('paginaInicio');
  if (!inicio || url !== '/pages/inicio.html') {
    HtmlHandle.htmlInsert(url, id);
  }

  //Si es INICIO que espere a encontrar el último pase de la animación
  if (url == '/pages/inicio.html') {
    let paginaInicio = document.getElementById('ultimoPase');
    if (paginaInicio) {
      tamañoPase();
    } else {
      setTimeout("tamañoPase()", 100);
    }
  }

  if(updateUrl) {
    HtmlHandle.updateUrl(path);
  }

  HtmlHandle.goToStart();
}

function obtenerPathName(){
  let path = window.location.pathname;
  path = path.split('/');
  console.log(path)
  path = path[1];
  return path;
}

function mostrarPaginaPorPathName(){
  let path = obtenerPathName();
  let updateUrl = false;
  console.log(path)

  if(path === undefined || path === ''){
    path = 'inicio';
    updateUrl = true;
  }

  mostrarPagina(path, updateUrl);
}

function gestionarCambioDePagina() {
  mostrarPaginaPorPathName();
}

// Registra el evento popstate
window.addEventListener('popstate', gestionarCambioDePagina);



