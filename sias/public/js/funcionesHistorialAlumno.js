document.addEventListener("DOMContentLoaded", function () {
  let boton = document.getElementById("btnEnviarArchivo");
  cargarArchivosAlumno(boton);
  console.log("ya se cargaron");
});

function modalManager(status) {
  let titleElement = document.getElementById("modal-notificacion-title");
  let bodyElement = document.getElementById("modal-notificacion-body");

  const messages = {
    "cita-notfound": ["Error", "Cita no encontrada"],
    "status-aceptada": ["Confirmación", "Asistencia confirmada correctamente"],
    "status-rechazada": ["Confirmación", "Asistencia"],
    unknown: ["Error", "Algo salió mal. Intenta de nuevo."],
  };
  const [title, body] = messages[status] || ["Aviso", "Respuesta desconocida."];
  titleElement.textContent = title;
  bodyElement.textContent = body;

  $("#modalNotificacion").modal("show");
  setTimeout(() => {
    $("#modalNotificacion").modal("hide");
  }, 10000);
}

function confirmarAsistencia(idCita, valorAsistencia) {
  fetch(`/admin/historial-alumno/confirmar-cita/${idCita}`, {
    method: "PATCH",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      valorAsistencia: valorAsistencia,
    }),
  })
    .then(async (response) => {
      let data = await response.json();
      let status = data.message || data.error || "uknown";
      modalManager(status);
    })
    .catch((error) => {
      console.error("Error en la solicitud:", error);
    });
}

function editarNota(idCita, idNota) {
  const divNotaParent = document.getElementById(`divNotaP${idNota}`);
  const divNotaChild = document.getElementById(`divNotaC${idNota}`);
  const contenido = document
    .getElementById(`contenidoNota${idNota}`)
    .textContent.trim();

  // Guarda HTML original para poder restaurarlo
  divNotaParent.setAttribute("data-original", divNotaChild.outerHTML);

  // Elimina el contenido actual
  divNotaChild.remove();

  // Crear nuevo contenedor editable
  const contenedorEditable = document.createElement("div");
  contenedorEditable.style.border = "1px solid black";
  contenedorEditable.style.borderRadius = "5px";
  contenedorEditable.style.padding = "15px";

  const textarea = document.createElement("textarea");
  textarea.value = contenido;
  textarea.style.width = "100%";
  textarea.style.minHeight = "100px";
  textarea.style.marginBottom = "10px";

  // Botón Guardar
  const btnGuardar = document.createElement("button");
  btnGuardar.textContent = "Guardar";
  btnGuardar.className = "btn btn-success";
  btnGuardar.style.marginRight = "10px";
  btnGuardar.onclick = function () {
    fetch(`/admin/historial-alumno/guardar-nota/${idCita}`, {
      method: "PATCH",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        contenido: textarea.value,
        idNota: idNota,
      }),
    })
      .then(async (response) => {
        let data = await response.json();
        let status = data.message || data.error || "uknown";
        modalManager(status);
        cancelarEdicion(idNota, textarea.value);
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
      });
  };

  const btnCancelar = document.createElement("button");
  btnCancelar.textContent = "Cancelar";
  btnCancelar.className = "btn btn-danger";
  btnCancelar.onclick = function () {
    cancelarEdicion(idNota);
  };

  contenedorEditable.appendChild(textarea);
  contenedorEditable.appendChild(btnGuardar);
  contenedorEditable.appendChild(btnCancelar);

  divNotaParent.appendChild(contenedorEditable);
}

function cancelarEdicion(idNota, nuevoContenido = null) {
  const divNotaParent = document.getElementById(`divNotaP${idNota}`);
  const originalHTML = divNotaParent.getAttribute("data-original");

  // Restaurar estructura original
  divNotaParent.innerHTML = originalHTML;

  // Si se guardó un nuevo contenido, actualízalo
  if (nuevoContenido !== null) {
    const article = divNotaParent.querySelector(`#contenidoNota${idNota}`);
    if (article) article.textContent = nuevoContenido;
  }
}

function showEliminarNota(idCita, idNota) {
  $("#modalEliminarNota").modal("show");
  document.getElementById("idCitaEliminar").value = idCita;
  document.getElementById("idNotaEliminar").value = idNota;
}

function eliminarNota() {
  $("#modalEliminarNota").modal("hide");
  let idCita = document.getElementById("idCitaEliminar").value;
  let idNota = document.getElementById("idNotaEliminar").value;

  fetch(`/admin/historial-alumno/eliminar-nota/${idCita}`, {
    method: "PATCH",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      idNota: idNota,
    }),
  })
    .then(async (response) => {
      let data = await response.json();
      let status = data.message || data.error || "uknown";
      modalManager(status);
      document.getElementById(`notaElemento${idNota}`).remove();
    })
    .catch((error) => {
      console.error("Error en la solicitud:", error);
    });
}

function renderizarNotaGuardada(nota, idCita) {
  const divNota = document.createElement("div");
  divNota.style.display = "flex";
  divNota.style.justifyContent = "space-between";
  divNota.style.marginBottom = "15px";

  const divNotaP = document.createElement("div");
  divNotaP.id = `divNotaP${nota.id}`;
  divNotaP.style.flex = "1";
  divNotaP.style.padding = "20px";
  divNotaP.style.marginRight = "10px";

  const divNotaC = document.createElement("div");
  divNotaC.id = `divNotaC${nota.id}`;
  divNotaC.style.flex = "1";
  divNotaC.style.border = "1px solid black";
  divNotaC.style.borderRadius = "5px";

  const divFecha = document.createElement("div");
  divFecha.style.display = "flex";
  divFecha.style.justifyContent = "end";
  divFecha.style.marginBottom = "5px";
  divFecha.style.marginRight = "10px";
  divFecha.innerHTML = `<strong>${nota.createdAt}</strong>`;

  const article = document.createElement("article");
  article.id = `contenidoNota${nota.id}`;
  article.style.flex = "1";
  article.style.padding = "15px";
  article.textContent = nota.descripcion;

  const divBotones = document.createElement("div");
  divBotones.style.display = "flex";
  divBotones.style.justifyContent = "start";
  divBotones.style.marginBottom = "5px";
  divBotones.style.marginRight = "10px";
  divBotones.innerHTML = `
        <button class="btn btn-default" style="margin-right: 20px; margin-left:10px;" onclick="editarNota(${idCita}, ${nota.id})">Editar</button>
        <button class="btn btn-default" onclick="showEliminarNota(${idCita}, ${nota.id})">Eliminar</button>
    `;

  divNotaC.appendChild(divFecha);
  divNotaC.appendChild(article);
  divNotaC.appendChild(divBotones);
  divNotaP.appendChild(divNotaC);
  divNota.appendChild(divNotaP);

  return divNota;
}

function nuevaNota(idCita) {
  // Buscar el div donde se agregará la nueva nota (antes del botón "+ Nueva Nota")
  const divCita = document.querySelector(
    `[onclick="nuevaNota(${idCita})"]`
  ).parentElement;

  // Crear contenedor
  const divNuevaNota = document.createElement("div");
  divNuevaNota.id = `nuevaNotaCita${idCita}`;
  divNuevaNota.style.flex = "1";
  divNuevaNota.style.marginBottom = "15px";
  divNuevaNota.style.padding = "20px";

  const contenedor = document.createElement("div");
  contenedor.style.border = "1px solid black";
  contenedor.style.borderRadius = "5px";
  contenedor.style.padding = "15px";

  // Textarea
  const textarea = document.createElement("textarea");
  textarea.placeholder = "Escribe la nueva nota...";
  textarea.style.width = "100%";
  textarea.style.minHeight = "100px";
  textarea.style.marginBottom = "10px";

  // Botón Guardar
  const btnGuardar = document.createElement("button");
  btnGuardar.textContent = "Guardar";
  btnGuardar.className = "btn btn-success";
  btnGuardar.style.marginRight = "10px";
  btnGuardar.onclick = function () {
    fetch(`/admin/historial-alumno/guardar-nueva-nota/${idCita}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        contenido: textarea.value,
      }),
    })
      .then(async (response) => {
        const data = await response.json();
        if (data && data.nota) {
          const nuevaNotaDiv = document.getElementById(
            `nuevaNotaCita${idCita}`
          );
          const nuevaNotaRender = renderizarNotaGuardada(data.nota, idCita);
          nuevaNotaDiv.replaceWith(nuevaNotaRender);
        } else {
          modalManager(data.message || "Error al guardar");
        }
      })
      .catch((error) => {
        console.error("Error al guardar nueva nota:", error);
      });
  };

  // Botón Cancelar
  const btnCancelar = document.createElement("button");
  btnCancelar.textContent = "Cancelar";
  btnCancelar.className = "btn btn-danger";
  btnCancelar.onclick = function () {
    cancelarNuevaNota(idCita);
  };

  contenedor.appendChild(textarea);
  contenedor.appendChild(btnGuardar);
  contenedor.appendChild(btnCancelar);

  divNuevaNota.appendChild(contenedor);
  divCita.insertBefore(
    divNuevaNota,
    divCita.querySelector('button[onclick^="nuevaNota"]')
  );
}

function cancelarNuevaNota(idCita) {
  const nuevaNota = document.getElementById(`nuevaNotaCita${idCita}`);
  if (nuevaNota) {
    nuevaNota.remove();
  }
}

function verSiHayArchivo() {
  const fileInput = document.getElementById("archivo");
  if (fileInput.files.length === 0) {
    document.getElementById("btnEnviarArchivo").disabled = true;
    return;
  } else if (fileInput.files.length > 0) {
    document.getElementById("btnEnviarArchivo").disabled = false;
  }
}
function subirArchivo(boton) {
  const fileInput = document.getElementById("archivo");
  const numeroDeCuenta = boton.getAttribute("data-numerocuenta");
  const carrera = boton.getAttribute("data-carrera");
  const generacion = boton.getAttribute("data-generacion");
  const descripcion = document.getElementById("descripcionArchivo").value;
  const formData = new FormData();

  formData.append("archivo", fileInput.files[0]);
  formData.append("carrera", carrera);
  formData.append("generacion", generacion);
  formData.append("numeroDeCuenta", numeroDeCuenta);
  formData.append("descripcion", descripcion);

  fetch("/admin/historial-alumno/subir-documento/", {
    method: "POST",
    body: formData,
  })
    .then(async (res) => {
      let data = await res.json();
      let status = data.message || data.error || "unknown";
      let nombre = data.nombreDocumento;
      console.log(nombre);
      modalManager(status);
      document.getElementById("archivo").value = "";
      document.getElementById("descripcionArchivo").value = "";
    })
    .catch((error) => {
      console.error(error);
      alert(error);
    });
}

function cargarArchivosAlumno(boton) {
  const carrera = boton.getAttribute("data-carrera");
  const generacion = boton.getAttribute("data-generacion");
  const numeroDeCuenta = boton.getAttribute("data-numerocuenta");

  const formData = new FormData();
  formData.append("carrera", carrera);
  formData.append("generacion", generacion);
  formData.append("numeroDeCuenta", numeroDeCuenta);

  fetch("/admin/historial-alumno/listar-documentos/", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      /*const tbody = document.getElementById("bodyDocumentos");
      tbody.innerHTML = ""; // Limpiar tabla
      if (data.archivos && data.archivos.length > 0) {
        data.archivos.forEach((archivo) => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td style="border: 1px solid black;">${archivo.nombre}</td>
            <td style="border: 1px solid black;">${archivo.fecha}</td>
            <td style="border: 1px solid black;">
              <a href="${archivo.url}" target="_blank" download>
                Descargar
              </a>
            </td>
          `;
          tbody.appendChild(tr);
          console.log(archivo.url);
        });
      } else {
        const tr = document.createElement("tr");
        tr.innerHTML = `
      <td colspan="3" style="text-align:center; border: 1px solid black;">NO HAY ARCHIVOS PARA ESTE ALUMNO</td>
    `;
        tbody.appendChild(tr);
      }*/
    })
    .catch((err) => {
      console.error("Error al cargar archivos", err);
    });
}

function descargarDocumento(idDocumento, carrera, generacion, numeroCuenta){
  fetch(`/admin/historial-alumno/descargar-documento/${idDocumento}`, {
    method: "PATCH",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      carrera: carrera,
      generacion: generacion,
      numeroDeCuenta: numeroCuenta,
    }),
  })
    .then(async (response) => {
      let data = await response.json();
      let status = data.message || data.error || "uknown";
      modalManager(status);
      document.getElementById(`notaElemento${idNota}`).remove();
    })
    .catch((error) => {
      console.error("Error en la solicitud:", error);
    });
}