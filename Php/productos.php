<?php
    $array=array();
    require_once('./handlers/db_handler.php');
    // Evalua si hay sesion iniciada
            $conex = new db_handler();
            $result = $conex->query2("select nombre,categoria,precio
                                   from productos");
        if(!empty($result)){
            foreach ($result as $fila) {
                array_push($array,$fila);
            }
        $response = array(
            'status' => 'success',
            'message' => 'Productos cargados',
            'productos' => $array,

        );
        die(json_encode($response));
        }
        else{
            $response = array(
                'status' => 'error',
                'message' => 'Productos no cargados',
    
            );
            die(json_encode($response));
        }
