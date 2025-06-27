document.addEventListener('DOMContentLoaded', function () {
});

let busqueda = "";

function buscarAlumno(input) {
    busqueda = input.value.trim();

    const contenedor = document.getElementById('resultadoAlumnos');
    const mensajeInicial = document.getElementById('mensajeInicial');

    if (!contenedor || !mensajeInicial) {
        console.error("No se encontró alguno de los elementos necesarios");
        return;
    }

    if (busqueda === "") {
        mensajeInicial.style.display = "block";
        contenedor.innerHTML = "";
        contenedor.style.display = "none";
        return;
    }

    mensajeInicial.style.display = "none";

    fetch(`/admin/get-alumnos?busqueda=${encodeURIComponent(busqueda)}`)
        .then(response => {
            if (!response.ok) throw new Error("Error al obtener alumnos.");
            return response.json();
        })
        .then(data => {
            contenedor.innerHTML = "";

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(alumno => {
                    const enlace = document.createElement('a');
                    enlace.href = `./historial-alumno/${alumno.numero_de_cuenta}`;
                    enlace.style.textDecoration = 'none';
                    enlace.style.color = 'inherit';

                    enlace.innerHTML = `
                        <div class="opcionAlumno" onmouseover="this.style.backgroundColor='#f0f8ff'" onmouseout="this.style.backgroundColor='white'">
                            <strong>${alumno.nombre} ${alumno.apellidos}</strong> — ${alumno.numero_de_cuenta}<br>
                            <span style="color: gray;">${alumno.carrera}</span>
                        </div>
                    `;

                    contenedor.appendChild(enlace);
                });

                contenedor.style.display = "block";
            } else {
                contenedor.innerHTML = `<p style="color: gray;">No hay alumnos con los criterios: <strong>${busqueda}</strong></p>`;
                contenedor.style.display = "block";
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Ocurrió un error al cargar los alumnos.");
        });
}


