<?php
// crud.php

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];

    if ($tipo == 'paciente') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];

        if (empty($id)) {
            $sql = "INSERT INTO pacientes (nombre, apellido, telefono, correo, fecha_nacimiento) 
                    VALUES ('$nombre', '$apellido', '$telefono', '$correo', '$fecha_nacimiento')";
        } else {
            $sql = "UPDATE pacientes 
                    SET nombre='$nombre', apellido='$apellido', telefono='$telefono', correo='$correo', fecha_nacimiento='$fecha_nacimiento' 
                    WHERE id=$id";
        }
        if ($conn->query($sql) === TRUE) {
            header("Location: indexcrud.php");  
            exit(); 
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

    } elseif ($tipo == 'cita') {
        $id = $_POST['id'];
        $paciente_id = $_POST['paciente_id'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $motivo = $_POST['motivo'];
        $estado = $_POST['estado'];

        if (empty($id)) {
            // Crear nueva cita
            $sql = "INSERT INTO citas (paciente_id, fecha, hora, motivo, estado) 
                    VALUES ($paciente_id, '$fecha', '$hora', '$motivo', '$estado')";
        } else {
            // Actualizar cita existente
            $sql = "UPDATE citas 
                    SET paciente_id=$paciente_id, fecha='$fecha', hora='$hora', motivo='$motivo', estado='$estado' 
                    WHERE id=$id";
        }
        // Redirigir a la lista de citas después de crear/actualizar
        if ($conn->query($sql) === TRUE) {
            header("Location: indexcrud.php");  
            exit(); 
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }


    } elseif ($tipo == 'expediente') {
        
        $paciente_id = $_POST['paciente_id'];
        $antecedentes = $_POST['antecedentes'];
        $alergias = $_POST['alergias'];
        $observaciones = $_POST['observaciones'];

        // Verificar si ya existe un expediente para este paciente
        $check_expediente = $conn->query("SELECT id FROM expedientes WHERE paciente_id = $paciente_id");
        if ($check_expediente->num_rows > 0) {
            // Actualizar el expediente existente
            $sql = "UPDATE expedientes 
                    SET antecedentes='$antecedentes', alergias='$alergias', observaciones='$observaciones', 
                    ultima_actualizacion=NOW() 
                    WHERE paciente_id=$paciente_id";
        } else {
            // Crear un nuevo expediente
            $sql = "INSERT INTO expedientes (paciente_id, antecedentes, alergias, observaciones) 
                    VALUES ($paciente_id, '$antecedentes', '$alergias', '$observaciones')";
        }
        if ($conn->query($sql) === TRUE) {
            header("Location: expediente.php?paciente_id=$paciente_id");  // Redirigir al expediente
            exit(); // Añadido exit()
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }


    } elseif ($tipo == 'odontograma') {
    $paciente_id = $_POST['paciente_id'];

    // Verificar si se seleccionó un archivo
    if (!isset($_FILES["odontograma"]) || $_FILES["odontograma"]["error"] == UPLOAD_ERR_NO_FILE) {
        echo "<script>alert('No se seleccionó ningún archivo.'); window.history.back();</script>";
        exit();
    }

    // Ruta de destino
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["odontograma"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si el archivo es una imagen
    $check = getimagesize($_FILES["odontograma"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('El archivo no es una imagen.'); window.history.back();</script>";
        $uploadOk = 0;
    }

    // Validar formatos permitidos
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "<script>alert('Solo se permiten archivos JPG, JPEG, PNG y GIF.'); window.history.back();</script>";
        $uploadOk = 0;
    }

    // Si todo está correcto, mover el archivo
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["odontograma"]["tmp_name"], $target_file)) {
            $odontograma_path = $target_file;

            // Actualizar base de datos
            $sql = "UPDATE expedientes 
                    SET odontograma='$odontograma_path', ultima_actualizacion=NOW() 
                    WHERE paciente_id=$paciente_id";

            if ($conn->query($sql) === TRUE) {
                header("Location: expediente.php?paciente_id=$paciente_id");
                exit();
            } else {
                echo "<script>alert('Error al actualizar la base de datos.'); window.history.back();</script>";
            }

        } else {
            echo "<script>alert('Hubo un error al subir el archivo.'); window.history.back();</script>";
        }
    }
}


    elseif ($tipo == 'tratamiento') {
        $id = $_POST['id'];
        $cita_id = $_POST['cita_id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (empty($id)) {
            // Crear nuevo tratamiento
            $sql = "INSERT INTO tratamientos (cita_id, nombre, descripcion) 
                    VALUES ($cita_id, '$nombre', '$descripcion')";
        } else {
            // Actualizar tratamiento existente
            $sql = "UPDATE tratamientos 
                    SET cita_id=$cita_id, nombre='$nombre', descripcion='$descripcion' 
                    WHERE id=$id";
        }
        if ($conn->query($sql) === TRUE) {
            header("Location: indexcrud.php"); // Redirigir a la lista de tratamientos
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($sql)) {
        if ($conn->query($sql) === TRUE) {
            if ($tipo == 'expediente' || $tipo == 'odontograma') {
                // Mantener la redirección existente para estos tipos
                if (isset($_POST['paciente_id'])) {  // Asegurar que paciente_id está definido
                    header("Location: expediente.php?paciente_id=" . $_POST['paciente_id']);
                } else {
                    header("Location: indexcrud.php"); // Redirección por defecto si no hay paciente_id
                }
            } else {
                header("Location: indexcrud.php"); // Redirige a indexcrud para paciente, cita, tratamiento
            }
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error al procesar la solicitud.";
    }


} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id']) && isset($_GET['tipo'])) {
    $id = $_GET['delete_id'];
    $tipo = $_GET['tipo'];

    if ($tipo == 'paciente') {
        $sql = "DELETE FROM pacientes WHERE id=$id";
    } elseif ($tipo == 'cita') {
        $sql = "DELETE FROM citas WHERE id=$id";
    }  elseif ($tipo == 'tratamiento') {
        $sql = "DELETE FROM tratamientos WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: indexcrud.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>