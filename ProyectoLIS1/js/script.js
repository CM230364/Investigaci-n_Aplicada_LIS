// script.js

// PACIENTES
const formularioPaciente = document.getElementById('formulario-paciente');
const pacienteForm = document.getElementById('paciente-form');
const agregarPacienteBtn = document.getElementById('agregar-paciente');
const editarPacienteBtn = document.getElementById('editar-paciente');
const eliminarPacienteBtn = document.getElementById('eliminar-paciente');
const verExpedienteBtn = document.getElementById('ver-expediente');
let pacientesTable = document.getElementById('pacientes-table');
let pacienteSeleccionado = null;
const cancelarFormularioPacienteBtn = document.getElementById('cancelar-formulario-paciente'); // Nuevo botón

// CITAS
const formularioCita = document.getElementById('formulario-cita');
const citaForm = document.getElementById('cita-form');
const agregarCitaBtn = document.getElementById('agregar-cita');
const editarCitaBtn = document.getElementById('editar-cita');
const eliminarCitaBtn = document.getElementById('eliminar-cita');
let citasTable = document.getElementById('citas-table');
let citaSeleccionada = null;
const cancelarFormularioCitaBtn = document.getElementById('cancelar-formulario-cita'); // Nuevo botón

// TRATAMIENTOS
const formularioTratamiento = document.getElementById('formulario-tratamiento');
const tratamientoForm = document.getElementById('tratamiento-form');
const agregarTratamientoBtn = document.getElementById('agregar-tratamiento');
const editarTratamientoBtn = document.getElementById('editar-tratamiento');
const eliminarTratamientoBtn = document.getElementById('eliminar-tratamiento');
let tratamientosTable = document.getElementById('tratamientos-table');
let tratamientoSeleccionado = null;
const cancelarFormularioTratamientoBtn = document.getElementById('cancelar-formulario-tratamiento'); // Nuevo botón


// PACIENTES

// Mostrar formulario vacío al agregar paciente
agregarPacienteBtn.addEventListener('click', () => {
    formularioPaciente.style.display = 'block';
    pacienteForm.reset();
    document.getElementById('paciente-id').value = '';
});

// Mostrar datos del paciente al seleccionar para editar
editarPacienteBtn.addEventListener('click', () => {
    if (pacienteSeleccionado) {
        formularioPaciente.style.display = 'block';
        document.getElementById('paciente-id').value = pacienteSeleccionado.id;
        pacienteForm.nombre.value = pacienteSeleccionado.nombre;
        pacienteForm.apellido.value = pacienteSeleccionado.apellido;
        pacienteForm.telefono.value = pacienteSeleccionado.telefono;
        pacienteForm.correo.value = pacienteSeleccionado.correo;
        pacienteForm.fecha_nacimiento.value = pacienteSeleccionado.fecha_nacimiento;
    }
});

// Ocultar formulario al hacer clic en cancelar
if (cancelarFormularioPacienteBtn) {
    cancelarFormularioPacienteBtn.addEventListener('click', () => {
        formularioPaciente.style.display = 'none';
    });
}

// Eliminar paciente
eliminarPacienteBtn.addEventListener('click', () => {
    if (pacienteSeleccionado) {
        window.location.href = `crud.php?delete_id=${pacienteSeleccionado.id}&tipo=paciente`;
    }
});

// Ver expediente del paciente
verExpedienteBtn.addEventListener('click', () => {
    if (pacienteSeleccionado) {
        window.location.href = `expediente.php?paciente_id=${pacienteSeleccionado.id}`;
    }
});


// CITAS

// Mostrar formulario vacío al agregar cita
agregarCitaBtn.addEventListener('click', () => {
    formularioCita.style.display = 'block';
    citaForm.reset();
    document.getElementById('cita-id').value = '';
});

// Mostrar datos de la cita al seleccionar para editar
editarCitaBtn.addEventListener('click', () => {
    if (citaSeleccionada) {
        formularioCita.style.display = 'block';
        document.getElementById('cita-id').value = citaSeleccionada.id;
        citaForm.paciente_id.value = citaSeleccionada.paciente_id;
        citaForm.fecha.value = citaSeleccionada.fecha;
        citaForm.hora.value = citaSeleccionada.hora;
        citaForm.motivo.value = citaSeleccionada.motivo;
        citaForm.estado.value = citaSeleccionada.estado;
    }
});

// Ocultar formulario al hacer clic en cancelar
if (cancelarFormularioCitaBtn) {
    cancelarFormularioCitaBtn.addEventListener('click', () => {
        formularioCita.style.display = 'none';
    });
}

// Eliminar cita
eliminarCitaBtn.addEventListener('click', () => {
    if (citaSeleccionada) {
        window.location.href = `crud.php?delete_id=${citaSeleccionada.id}&tipo=cita`;
    }
});

// Seleccionar paciente en la tabla
pacientesTable.addEventListener('click', (event) => {
    if (event.target.tagName === 'TD') {
        const row = event.target.parentNode;
        const cells = row.getElementsByTagName('TD');
        pacienteSeleccionado = {
            id: cells[0].textContent,
            nombre: cells[1].textContent,
            apellido: cells[2].textContent,
            telefono: cells[3].textContent,
            correo: cells[4].textContent,
            fecha_nacimiento: cells[5].textContent
        };
    }
});

// Seleccionar cita en la tabla
citasTable.addEventListener('click', (event) => {
    if (event.target.tagName === 'TD') {
        const row = event.target.parentNode;
        const cells = row.getElementsByTagName('TD');
        citaSeleccionada = {
            id: cells[0].textContent,
            paciente_id: cells[1].textContent,
            fecha: cells[2].textContent,
            hora: cells[3].textContent,
            motivo: cells[4].textContent,
            estado: cells[5].textContent
        };
    }
});

// TRATAMIENTOS

// Mostrar formulario vacío al agregar tratamiento
agregarTratamientoBtn.addEventListener('click', () => {
    formularioTratamiento.style.display = 'block';
    tratamientoForm.reset();
    document.getElementById('tratamiento-id').value = '';
});

// Mostrar datos del tratamiento al seleccionar para editar
editarTratamientoBtn.addEventListener('click', () => {
    if (tratamientoSeleccionado) {
        formularioTratamiento.style.display = 'block';
        document.getElementById('tratamiento-id').value = tratamientoSeleccionado.id;
        tratamientoForm.cita_id.value = tratamientoSeleccionado.cita_id;
        tratamientoForm.nombre.value = tratamientoSeleccionado.nombre;
        tratamientoForm.descripcion.value = tratamientoSeleccionado.descripcion;
    }
});

// Ocultar formulario al hacer clic en cancelar
if (cancelarFormularioTratamientoBtn) {
    cancelarFormularioTratamientoBtn.addEventListener('click', () => {
        formularioTratamiento.style.display = 'none';
    });
}

// Eliminar tratamiento
eliminarTratamientoBtn.addEventListener('click', () => {
    if (tratamientoSeleccionado) {
        window.location.href = `crud.php?delete_id=${tratamientoSeleccionado.id}&tipo=tratamiento`;
    }
});

// Seleccionar tratamiento en la tabla
tratamientosTable.addEventListener('click', (event) => {
    if (event.target.tagName === 'TD') {
        const row = event.target.parentNode;
        const cells = row.getElementsByTagName('TD');
        tratamientoSeleccionado = {
            id: cells[0].textContent,
            cita_id: cells[1].textContent,
            nombre: cells[5].textContent,
            descripcion: cells[6].textContent
        };
    }
});
