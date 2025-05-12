// script.js

// PACIENTES
const formularioPaciente = document.getElementById('formulario-paciente');
const pacienteForm = document.getElementById('paciente-form');
const agregarPacienteBtn = document.getElementById('agregar-paciente');
const editarPacienteBtn = document.getElementById('editar-paciente');
const eliminarPacienteBtn = document.getElementById('eliminar-paciente');
let pacientesTable = document.getElementById('pacientes-table');
let pacienteSeleccionado = null;

// CITAS
const formularioCita = document.getElementById('formulario-cita');
const citaForm = document.getElementById('cita-form');
const agregarCitaBtn = document.getElementById('agregar-cita');
const editarCitaBtn = document.getElementById('editar-cita');
const eliminarCitaBtn = document.getElementById('eliminar-cita');
let citasTable = document.getElementById('citas-table');
let citaSeleccionada = null;


// PACIENTES

// Mostrar formulario vacío al agregar paciente
agregarPacienteBtn.addEventListener('click', () => {
    formularioPaciente.style.display = 'block';})