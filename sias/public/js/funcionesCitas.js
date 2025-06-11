document.addEventListener('DOMContentLoaded', function () {

    const cicloSelect = document.getElementById('ciclos');
    const primerCiclo = cicloSelect.value;

    if (primerCiclo) {
        loadPeriodos(primerCiclo);
    }

});

function handleSubmit(event) {
  event.preventDefault();

  $('#modalAsignacion').modal('hide');

  let id = document.getElementById("idCita").value;

  let submitter = event.submitter;
  let accion = submitter.value;
  if (accion == "asignar") {
    asignarPsicologo(id);
  } else if (accion == "rechazar") {
    rechazarCita(id);
  }
}

function asignarPsicologo(citaId) {
  let psicologoId = document.getElementById("psicologo-select").value;

  fetch(`/admin/citas/asignar-psicologo/${citaId}`, {
    method: "PATCH",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      psicologo_id: psicologoId,
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

function rechazarCita(citaId) {
  fetch(`/admin/citas/rechazar-cita/${citaId}`, {
    method: "PATCH",
    headers: {},
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

function modalManager(status) {
  let titleElement = document.getElementById("modal-notificacion-title");
  let bodyElement = document.getElementById("modal-notificacion-body");

  const messages = {
    "cita-notfound": ["Error", "Cita no encontrada"],
    "psicologo-notfound": ["Error", "Psicólogo no encontrado"],
    "status-aceptada": ["Confirmación", "Cita asignada correctamente"],
    "status-rechazada": ["Confirmación", "Cita rechazada"],
    "unknown": ["Error", "Algo salió mal. Intenta de nuevo."]
  };
  const [title, body] = messages[status] || ["Aviso", "Respuesta desconocida."];
  titleElement.textContent = title;
  bodyElement.textContent = body;

  $('#modalNotificacion').modal('show');
  setTimeout(() => {
    $('#modalNotificacion').modal('hide');
  }, 2000);

  loadCitas();
}

function loadPeriodos(ciclo) {
    fetch(`/admin/get-periodos-by-ciclo?ciclo=${encodeURIComponent(ciclo)}`)
        .then(response => {
            if (!response.ok) throw new Error("Error al obtener los periodos.");
            return response.json();
        })
        .then(data => {
            const periodosSelect = document.getElementById('periodos');
            periodosSelect.innerHTML = '';

            data.forEach(periodo => {
                const option = document.createElement('option');
                option.value = periodo.id;
                option.textContent = `${periodo.fechaInicio} a ${periodo.fechaFin}`;
                periodosSelect.appendChild(option);
            });
            console.log(data);
            
            loadCitas();
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Ocurrió un error al cargar los periodos.");
        });
        
       
}

function loadCitas() {
    let periodo = document.getElementById("periodos").value;
    let estado = document.getElementById("estados").value;
     
    //console.log(periodo);
    //console.log(estado);
    fetch(`/admin/get-citas?periodo=${encodeURIComponent(periodo)}&estado=${encodeURIComponent(estado)}`)
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || "Error desconocido del servidor.");
        }
        return data;
    })
    .then(data => {
      console.log(data);
      let tabla = document.getElementById("citas");
      tabla.innerHTML = "";
      if (data.length === 0) {
            const filaVacia = document.createElement('tr');
            const celda = document.createElement('td');
            celda.setAttribute('colspan', '4'); // Ajusta el número si hay más columnas
            celda.textContent = "No hay citas para el periodo y estado seleccionado.";
            celda.style.textAlign = "center";
            filaVacia.appendChild(celda);
            tabla.appendChild(filaVacia);
            return; // salir del .then
        }
      data.forEach(item => {
        const nuevaFila = document.createElement('tr');

        if(item.usuario_psicologo_id){
          //console.log(item.usuario_psicologo_id)
          nuevaFila.setAttribute('data-psicologo', item.usuario_psicologo_id);
        }
        
        const tdIdUsuarioAragon = document.createElement('td');
        tdIdUsuarioAragon.textContent = item.usuario_aragon_id;
        nuevaFila.appendChild(tdIdUsuarioAragon);

        const tdNombreUsuarioAragon = document.createElement('td');
        tdNombreUsuarioAragon.textContent = item.usuario_aragon_nombre + " " + item.usuario_aragon_apellidos;
        nuevaFila.appendChild(tdNombreUsuarioAragon);

        const tdFechaCita = document.createElement('td');
        tdFechaCita.textContent = formatearFecha(item.fecha_cita) + " " + item.hora_inicio_cita;
        nuevaFila.appendChild(tdFechaCita);

        const tdPsicologo = document.createElement('td');
        tdPsicologo.textContent = item.usuario_psicologo_nombre + " " +item.usuario_psicologo_apellidos;
        nuevaFila.appendChild(tdPsicologo);

        const tdAcciones = document.createElement('td');
        tdAcciones.innerHTML = `<button class='btn btn-primary' data-toggle='modal' data-target='#modalAsignacion' onclick="settearCita(this, ${item.id}, '${item.usuario_aragon_nombre + ' ' + item.usuario_aragon_apellidos}', '${formatearFecha(item.fecha_cita)}', '${item.hora_inicio_cita}', '${item.hora_final_cita}');">Asignar Psicólogo</button>`;
        nuevaFila.appendChild(tdAcciones);
        tabla.appendChild(nuevaFila);
        if(estado == "ACEPTADA"){
          document.getElementById("botonAsignacion").textContent = "Cambiar";
        }else{
          document.getElementById("botonAsignacion").textContent = "Asignar";
        }
      });        
    })
    .catch(error => {
        console.error("Error detallado:", error);
        alert("Error al cargar citas: " + error.message);
    });

   
}

function settearCita(boton, idCita, nombre, fecha, horainicio, horafinal){
  let tr = boton.closest('tr');
  let psicologo = tr.getAttribute('data-psicologo');
  if(psicologo){
    let selectPsicologo = document.getElementById("psicologo-select");
    let option = selectPsicologo.querySelector(`option[value="${psicologo}"]`);

    if(option){
      selectPsicologo.value = psicologo;
      console.log("si tiene psicolog y ya se asignó");
    }else{
      console.log("No se encuentra el psiclogo");
    }
  }else{
    console.warn("El atributo data-psicologo esstá vacio");
  }

  document.getElementById("idCita").value = idCita;
  document.getElementById("usuario_aragon_cita").textContent = "Alumno: "+nombre;
  document.getElementById("fecha_cita").textContent = fecha + " " + horainicio + " - " + horafinal;
}

function formatearFecha(fechaISO) {
    const fecha = new Date(fechaISO);
    const opciones = {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    };

    return new Intl.DateTimeFormat('es-MX', opciones).format(fecha);
}

