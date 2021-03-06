<?php

    

    if (isset($_POST['registro'])) {
        include_once 'functions/funciones.php';

        switch ($_POST['registro']) {
            case 'nuevo':
                $usuario = $_POST['usuario'];
                $nombre = $_POST['nombre'];
                $pass = $_POST['pass'];

                $opciones = array(
                    'cost' => 12
                );
                $pass_hashed = password_hash($pass, PASSWORD_BCRYPT, $opciones);
                try {

                    $stmt = $conn->prepare("INSERT INTO admins (usuario, nombre, pass) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $usuario, $nombre, $pass_hashed);
                    $stmt->execute();
                    $id_registro = $stmt->insert_id;

                    if ($stmt->affected_rows > 0) {
                        $respuesta = array (
                            'respuesta' => 'exito',
                            'id_admin' => $id_registro,
                            'nombre' => $nombre
                        );
                        
                    } else {
                        $respuesta = array (
                            'respuesta' => 'error-registro',
                            'user' => $usuario
                        );
                    }

                    $stmt->close();
                    $conn->close();
                } catch (Exception $e) {
                    $respuesta = array (
                        'respuesta' => $e->getMessage()
                    );
                }

                die(json_encode($respuesta));
                break;
            
            case 'actualizar':
                $usuario = $_POST['usuario'];
                $nombre = $_POST['nombre'];
                $pass = $_POST['pass'];
                $id_registro = $_POST['id_registro'];

                try {
                    if (empty($_POST['pass'])) {
                        $stmt = $conn->prepare("UPDATE admins SET usuario = ?, nombre = ?, editado = NOW() WHERE id_admin = ?");
                        $stmt->bind_param("ssi", $usuario, $nombre, $id_registro);
                    } else {
                        $opciones = array(
                            'cost' => 12
                        );
                        $pass_hashed = password_hash($pass, PASSWORD_BCRYPT, $opciones);
                        $stmt = $conn->prepare("UPDATE admins SET usuario = ?, nombre = ?, pass = ?, editado = NOW() WHERE id_admin = ?");
                        $stmt->bind_param("sssi", $usuario, $nombre, $pass_hashed, $id_registro);
                    }
                    
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        $respuesta = array (
                            'respuesta' => 'correcto',
                            'id_actualizado' => $id_registro,
                        );
                    } else {
                        $respuesta = array (
                            'respuesta' => 'error'
                        );
                    }

                    $stmt->close();
                    $conn->close();
                } catch (Exception $e) {
                    $respuesta = array (
                        'respuesta' => $e->getMessage()
                    );
                }
                die(json_encode($respuesta));
                break;
            

            case 'eliminar':
                $id_borrar = $_POST['id'];

                try {
                    $stmt = $conn->prepare("DELETE FROM admins WHERE id_admin = ?");
                    $stmt->bind_param("i", $id_borrar);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        $respuesta = array (
                            'respuesta' => 'exito',
                            'id_eliminado' => $id_borrar
                        );
                    } else {
                        $respuesta = array (
                            'respuesta' => 'error'
                        );
                    }

                    $stmt->close();
                    $conn->close();
                } catch (Exception $e) {
                    $respuesta = array (
                        'respuesta' => $e->getMessage()
                    );
                }
                die(json_encode($respuesta));
                break;
            
            default:
                # code...
                break;
        }
        
    };
?>