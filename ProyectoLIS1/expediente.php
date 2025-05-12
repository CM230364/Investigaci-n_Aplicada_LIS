<?php
include 'conexion.php';
include 'init.php'; // Aseguramos que la sesión está iniciada

$paciente_id = $_GET['paciente_id'] ?? null; // Obtener el ID del paciente desde la URL

if (!$paciente_id) {
    echo "Paciente no seleccionado.";
    exit;
}

// Obtener los datos del paciente
$result_paciente = $conn->query("SELECT * FROM pacientes WHERE id = $paciente_id");
$paciente = $result_paciente->fetch_assoc();

// Obtener el expediente del paciente
$result_expediente = $conn->query("SELECT * FROM expedientes WHERE paciente_id = $paciente_id");
$expediente = $result_expediente->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Expediente de Paciente: <?= htmlspecialchars($paciente['nombre']) ?></title>
    <link rel="stylesheet" href="css/expedientes.css">
    
</head>

<body>
    <h1>Expediente de Paciente: <?= htmlspecialchars($paciente['nombre']) ?></h1>

    <p><strong>Nombre:</strong> <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']) ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($paciente['telefono']) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($paciente['correo']) ?></p>
    <p><strong>Fecha de Nacimiento:</strong> <?= $paciente['fecha_nacimiento'] ?></p>

    <?php if ($expediente): ?>
        <h2>Detalles del Expediente</h2>
        <p><strong>Antecedentes:</strong> <?= htmlspecialchars($expediente['antecedentes']) ?></p>
        <p><strong>Alergias:</strong> <?= htmlspecialchars($expediente['alergias']) ?></p>
        <p><strong>Observaciones:</strong> <?= htmlspecialchars($expediente['observaciones']) ?></p>

        <div class="odontograma-container">
            <?php if ($expediente['odontograma']): ?>
                <img src="<?= htmlspecialchars($expediente['odontograma']) ?>" alt="Odontograma" class="odontograma-image">
            <?php else: ?>
                <p>No hay odontograma registrado.</p>
            <?php endif; ?>

            <form id="form-odontograma" action="crud.php" method="post" enctype="multipart/form-data" class="upload-form">
                <input type="hidden" name="tipo" value="odontograma">
                <input type="hidden" name="paciente_id" value="<?= $paciente_id ?>">
                <input type="file" name="odontograma" accept="image/*">
                <button type="submit">Subir Odontograma</button>
            </form>
        </div>

        <p><strong>Última Actualización:</strong> <?= $expediente['ultima_actualizacion'] ?></p>

        <form action="crud.php" method="post">
            <input type="hidden" name="tipo" value="expediente">
            <input type="hidden" name="paciente_id" value="<?= $paciente_id ?>">
            <textarea name="antecedentes" placeholder="Antecedentes"><?= htmlspecialchars($expediente['antecedentes']) ?></textarea><br>
            <textarea name="alergias" placeholder="Alergias"><?= htmlspecialchars($expediente['alergias']) ?></textarea><br>
            <textarea name="observaciones" placeholder="Observaciones"><?= htmlspecialchars($expediente['observaciones']) ?></textarea><br>
            <button type="submit">Actualizar Expediente</button>
        </form>

    <?php else: ?>
        <h2>No hay Expediente Registrado</h2>
        <p>No se ha encontrado un expediente para este paciente. Puedes crear uno a continuación.</p>

        <form action="crud.php" method="post">
            <input type="hidden" name="tipo" value="expediente">
            <input type="hidden" name="paciente_id" value="<?= $paciente_id ?>">
            <textarea name="antecedentes" placeholder="Antecedentes"></textarea><br>
            <textarea name="alergias" placeholder="Alergias"></textarea><br>
            <textarea name="observaciones" placeholder="Observaciones"></textarea><br>
            <button type="submit">Crear Expediente</button>
        </form>
    <?php endif; ?>

</body>

</html>