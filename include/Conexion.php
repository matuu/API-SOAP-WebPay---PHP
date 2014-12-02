<?php

    function fnConexion(){
        try{
            $host = "localhost";
            $user = "root";
            $pass = "";
            $base = "bdtienda";

            $bd = new mysqli($host, $user, $pass, $base);

            if($bd->connect_errno != 0){
                $error = $bd->connect_error;
                $nroError = $bd->connect_errno;

                ClassNegocio::throwException("Hubo un problema de conexión con la base de datos.");
            }
            else{
                $bd->set_charset("utf8");
                return $bd;
            }
        }
        catch (Exception $e){
            ClassNegocio::throwException($e->getMessage());
        }
    }


