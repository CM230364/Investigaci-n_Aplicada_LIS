<?php
include 'conexion.php';
include 'init.php'; 
// Obtener pacientes
$result_pacientes = $conn->query("SELECT * FROM pacientes ORDER BY creado_en DESC");

// Obtener citas 
$result_citas = $conn->query("
    SELECT citas.*, pacientes.nombre as nombre_paciente 
    FROM citas 
    INNER JOIN pacientes ON citas.paciente_id = pacientes.id
    ORDER BY fecha DESC, hora ASC
");

// Obtener tratamientos 
$result_tratamientos = $conn->query("
    SELECT tratamientos.*, citas.fecha as fecha_cita, citas.hora as hora_cita, pacientes.nombre as nombre_paciente
    FROM tratamientos
    INNER JOIN citas ON tratamientos.cita_id = citas.id
    INNER JOIN pacientes ON citas.paciente_id = pacientes.id
    ORDER BY tratamientos.id DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Citas Dentales</title>
    <link rel="stylesheet" href="css/crud.css"> 
    <script defer src="js/script.js"></script>
</head>
<body>
   <div style="text-align: center; padding: 20px;">
  <img src="logos/dental.png" alt="Logo de la Clínica Dental Sonrisas" style="max-width: 150px; height: auto;">
  <h1 style="color: #007bff; margin-top: 15px; font-size: 2.5em;">Gestión de Citas Dentales</h1>
  <p style="color: #6c757d; font-size: 1.1em;">Tu sonrisa, nuestra prioridad.</p>
</div>
 
<div class="user-bar">
    <img src="logos/usuario.png" alt="Logo de Usuario" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;">
    <span>Usuario: <?php echo $_SESSION['usuario_nombre']; ?></span>
    <form action="logout.php" method="post">
        <button type="submit" class="logout-button">Cerrar Sesión</button>
    </form>
</div>

    <div>
        <h2>Pacientes</h2>
        <button id="agregar-paciente">Agregar Paciente</button>
        <button id="editar-paciente">Editar Paciente</button>
        <button id="eliminar-paciente">Eliminar Paciente</button>
        <button id="ver-expediente">Ver Expediente</button> 
    </div>

    <br>

    <table border="1" id="pacientes-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Fecha de Nacimiento</th>
                <th>Creado En</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result_pacientes->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['apellido']) ?></td>
                    <td><?= htmlspecialchars($row['telefono']) ?></td>
                    <td><?= htmlspecialchars($row['correo']) ?></td>
                    <td><?= $row['fecha_nacimiento'] ?></td>
                    <td><?= $row['creado_en'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>
    <div id="formulario-paciente" style="display: none; margin-top: 20px;">
        <form id="paciente-form" action="crud.php" method="POST">
            <input type="hidden" name="tipo" value="paciente"> 
            <input type="hidden" name="id" id="paciente-id">
            <label>Nombre: <input type="text" name="nombre" required></label><br>
            <label>Apellido: <input type="text" name="apellido"></label><br>
            <label>Teléfono: <input type="text" name="telefono"></label><br>
            <label>Correo: <input type="email" name="correo"></label><br>
            <label>Fecha de Nacimiento: <input type="date" name="fecha_nacimiento"></label><br>
            <button type="submit">Guardar Paciente</button>
            <button type="button" id="cancelar-formulario-paciente">Cancelar</button>
        </form>
    </div>

    <div>
        <h2>Citas</h2>
        <button id="agregar-cita">Agregar Cita</button>
        <button id="editar-cita">Editar Cita</button>
        <button id="eliminar-cita">Eliminar Cita</button>
    </div>

    <br>

    <table border="1" id="citas-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Paciente</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result_citas->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre_paciente']) ?></td>
                    <td><?= $row['fecha'] ?></td>
                    <td><?= $row['hora'] ?></td>
                    <td><?= htmlspecialchars($row['motivo']) ?></td>
                    <td><?= htmlspecialchars($row['estado']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>
    <div id="formulario-cita" style="display: none; margin-top: 20px;">
        <form id="cita-form" action="crud.php" method="POST">
            <input type="hidden" name="tipo" value="cita">
            <input type="hidden" name="id" id="cita-id">
            <label>Paciente: 
                <select name="paciente_id" required>
                    <?php 
                    // Volvemos a ejecutar la consulta para obtener la lista de pacientes
                    $result_pacientes_select = $conn->query("SELECT id, nombre FROM pacientes ORDER BY nombre ASC");
                    while($row = $result_pacientes_select->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                    <?php endwhile; ?>
                </select>
            </label><br>
            <label>Fecha: <input type="date" name="fecha" required></label><br>
            <label>Hora: <input type="time" name="hora" required></label><br>
            <label>Motivo: <textarea name="motivo"></textarea></label><br>
            <label>Estado: 
                <select name="estado">
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmada">Confirmada</option>
                    <option value="cancelada">Cancelada</option>
                    <option value="completada">Completada</option>
                </select>
            </label><br>
            <button type="submit">Guardar Cita</button>
            <button type="button" id="cancelar-formulario-cita">Cancelar</button>
        </form>
    </div>
    

    <div>
        <h2>Tratamientos</h2>
        <button id="agregar-tratamiento">Agregar Tratamiento</button>
        <button id="editar-tratamiento">Editar Tratamiento</button>
        <button id="eliminar-tratamiento">Eliminar Tratamiento</button>
    </div>

    <br>

    <table border="1" id="tratamientos-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cita ID</th>
                <th>Paciente</th>
                <th>Fecha Cita</th>
                <th>Hora Cita</th>
                <th>Tratamiento</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result_tratamientos->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['cita_id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre_paciente']) ?></td>
                    <td><?= $row['fecha_cita'] ?></td>
                    <td><?= $row['hora_cita'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['descripcion']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>


    <div id="formulario-tratamiento" style="display: none; margin-top: 20px;">
        <form id="tratamiento-form" action="crud.php" method="POST">
            <input type="hidden" name="tipo" value="tratamiento">
            <input type="hidden" name="id" id="tratamiento-id">
            <label>Cita: 
                <select name="cita_id" required>
                    <?php 
                    // Volvemos a ejecutar la consulta para obtener la lista de citas
                    $result_citas_select = $conn->query("SELECT id, fecha, hora FROM citas ORDER BY fecha ASC, hora ASC");
                    while($row = $result_citas_select->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['fecha'] ?> - <?= $row['hora'] ?></option>
                    <?php endwhile; ?>
                </select>
            </label><br>
            <label>Tratamiento: <input type="text" name="nombre" required></label><br>
            <label>Descripción: <textarea name="descripcion"></textarea></label><br>
            <button type="submit">Guardar Tratamiento</button>
            <button type="button" id="cancelar-formulario-tratamiento">Cancelar</button>
        </form>
    </div>

</body>
</html>