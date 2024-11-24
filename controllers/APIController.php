<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

// Consulta la base datos y retorna un JSON que se puede leer en JavaScript
class APIController {
    public static function index() {
        $servicios = Servicio::all(); // Tomamos todos los servicios del modelo
        echo json_encode($servicios); /* Se convierte a JSON y a su vez lo convertimos a objetos en JS
        (puesto que antes son arreglos asociativos y estos no existen en javaScript) */
    }
    public static function guardar() {
        // Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        // Almacena los servicios con el ID de la cita
        $idServicios = explode(",", $_POST['servicios']);
        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaservicio = new CitaServicio($args);
            $citaservicio->guardar();
        }
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}