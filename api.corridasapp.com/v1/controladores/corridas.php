<?php
// Constantes de estado

class corridas
{
    // Datos de la tabla "corridas"
    const NOMBRE_TABLE =      "corridas";
    const ID_CORRIDA =        "id_corrida";
    const ID_BUSLINE =        "id_busline";
    const ORIGEN =            "Origen";
    const DESTINO =           "Destino";
    const INICIAL_ORIGEN =    "Inicial_origen";
    const INICIAR_DESTINO =   "Inicial_destino";
    const HORA_DE_PARTIDA =   "Hora_de_partida";
    const HORA_DE_LLEGADA =   "hora_de_llegada";
    const DIAS_DE_OPERACION = "dias_de_operacion";
    const COSTO =             "costo";



    public static function get($peticion)
    {
        //  $Origen =corridas::obtenerOrigen();
        if (empty($peticion[0])) {
            return self::obtenercorridas();
        } else {
            //    return self::obtenercorridas();

            throw new ExcepcionApi("Url mal formada", 400);
        }
    }
    public function obtenerOrigen()
    {
        // $comando = "SELECT " . self::ID_CORRIDA . self::ID_BUSLINE . self::ORIGEN . self::DESTINO . self::INICIAL_ORIGEN . self::INICIAR_DESTINO . self::HORA_DE_PARTIDA . self::HORA_DE_LLEGADA . self::DIAS_DE_OPERACION . self::COSTO .
        $comando = "SELECT " . self::ORIGEN .
            " FROM " . self::NOMBRE_TABLE .
            " WHERE " . self::ORIGEN . "=?";

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

        // $sentencia->bindParam(1, $this-> self::Origen);
        $sentencia->bindParam(1, $Origen);

        if ($sentencia->execute()) {
            $resultado = $sentencia->fetch();
            return $resultado['Origen'];
        } else {
            return null;
        }
    }

    private function obtenercorridas()
    {
        try {

            $comando = "SELECT *" . " FROM " . self::NOMBRE_TABLE;
            // Preparar sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
            // Ejecutar sentencia preparada
            if ($sentencia->execute()) {
                $corridas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                return ["corridas" => $corridas];
            } else {
                throw new ExcepcionApi(2, "Se ha producido un error");
            }
        } catch (PDOException $e) {
            throw new ExcepcionApi(3, $e->getMessage());
        }
    }

    public static function post($peticion)
    {
        $body = file_get_contents('php://input');
        $corrida = json_decode($body);

        $id_corrida = corridas::crear($corrida);

        http_response_code(201);
        return [
            "estado" => 1,
            "mensaje" => "Horario de corrida creado",
            "id" => $id_corrida
        ];
    }
    private function crear($corrida)
    {

        if ($corrida) {
            try {

                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

                //Sentencia INSERT
                $comando = "INSERT INTO " . self::NOMBRE_TABLE . " ( " .
                    self::ID_BUSLINE . "," .
                    self::ORIGEN . "," .
                    self::DESTINO . "," .
                    self::INICIAL_ORIGEN . "," .
                    self::INICIAR_DESTINO . "," .
                    self::HORA_DE_PARTIDA . "," .
                    self::HORA_DE_LLEGADA . "," .
                    self::DIAS_DE_OPERACION . "," .
                    self::COSTO . ")" .
                    " VALUES(?,?,?,?,?,?,?,?,?)";

                // Preparar la sentencia
                $sentencia = $pdo->prepare($comando);

                $sentencia->bindParam(1, $id_busline);
                $sentencia->bindParam(2, $Origen);
                $sentencia->bindParam(3, $Destino);
                $sentencia->bindParam(4, $Inicial_origen);
                $sentencia->bindParam(5, $Inicial_destino);
                $sentencia->bindParam(6, $Hora_de_partida);
                $sentencia->bindParam(7, $hora_de_llegada);
                $sentencia->bindParam(8, $dias_de_operacion);
                $sentencia->bindParam(9, $costo);

                $id_busline =        $corrida->id_busline;
                $Origen =            $corrida->Origen;
                $Destino =           $corrida->Destino;
                $Inicial_origen =    $corrida->Inicial_origen;
                $Inicial_destino =   $corrida->Inicial_destino;
                $Hora_de_partida =   $corrida->Hora_de_partida;
                $hora_de_llegada =   $corrida->hora_de_llegada;
                $dias_de_operacion = $corrida->dias_de_operacion;
                $costo =             $corrida->costo;

                $sentencia->execute();

                // Retornar en el último id insertado
                return $pdo->lastInsertId();
            } catch (PDOException $e) {
                throw new ExcepcionApi(3, $e->getMessage());
            }
        } else {
            throw new ExcepcionApi(
                4,
                utf8_encode("Error en existencia o sintaxis de parámetros")
            );
        }
    }



    public static function put($peticion) ////need to work in this section
    {
        if (!empty($peticion[0])) {
            $body = file_get_contents('php://input');
            $corrida = json_decode($body);

            if (self::actualizar($corrida, $peticion[0]) > 0) {
                http_response_code(200);
                return [
                    "estado" => 1,
                    "mensaje" => "Registro de corrida actualizado correctamente"
                ];
            } else {
                throw new ExcepcionApi(
                    5,
                    "El contacto al que intentas acceder no existe",
                    404
                );
            }
        } else {
            throw new ExcepcionApi(4, "Falta id", 422);
        }
    }
    private function actualizar($corrida)
    
    {
        $id_corridax = $corrida['id_corrida'];
        try {
            //Creando consulta UPDATE
            $consulta = "UPDATE " . self::NOMBRE_TABLE .
                " SET " . 
                self::ID_BUSLINE . "=?," .
                self::ORIGEN . "=?," .
                self::DESTINO . "=?," .
                self::INICIAL_ORIGEN . "=?," .
                self::INICIAR_DESTINO . "=?," .
                self::HORA_DE_PARTIDA . "=?," .
                self::HORA_DE_LLEGADA . "=?," .
                self::DIAS_DE_OPERACION . "=?," .
                self::COSTO . "=? " .
                " WHERE " . self::ID_CORRIDA . "=? ";

            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($consulta);

                      
            $sentencia->bindParam(1, $id_corridax);
            $sentencia->bindParam(2, $id_busline);                   
            $sentencia->bindParam(3, $Origen);
            $sentencia->bindParam(4, $Destino);
            $sentencia->bindParam(5, $Inicial_origen);
            $sentencia->bindParam(6, $Inicial_destino);
            $sentencia->bindParam(7, $Hora_de_partida);
            $sentencia->bindParam(8, $hora_de_llegada);
            $sentencia->bindParam(9, $dias_de_operacion);
            $sentencia->bindParam(8, $costo);    
            $sentencia->bindParam(1, $id_corrida);
            $sentencia->bindParam(2, $id_busline);        
           
          
            $id_corrida =        $corrida->id_corridax;
            $id_busline =        $corrida->id_busline;
            $Origen =            $corrida->Origen;
            $Destino =           $corrida->Destino;
            $Inicial_origen =    $corrida->Inicial_origen;
            $Inicial_destino =   $corrida->Inicial_destino;
            $Hora_de_partida =   $corrida->Hora_de_partida;
            $hora_de_llegada =   $corrida->hora_de_llegada;
            $dias_de_operacion = $corrida->dias_de_operacion;
            $costo =             $corrida->costo;

            // Ejecutar la sentencia
        $sentencia->execute();

        return $sentencia->rowCount();
        } catch (PDOException $e) {
            throw new ExcepcionApi(3, $e->getMessage());
        }
    }
    public static function delete($peticion)
    {
        if (!empty($peticion[0])) {
            if (self::eliminar($peticion[0]) > 0) {
                http_response_code(200);
                return [
                    "estado" => 1,
                    "mensaje" => "Registro eliminado correctamente",
                    
                ];            
            }else{
                throw new ExcepcionApi(5,
                "El contacto al que intentas acceder no existe", 404);
            }
        }else{
            throw new ExcepcionApi(4, "Falta id", 422);
        }
    }

    private function eliminar($corrida){

        $id_corridax = $corrida['id_corrida'];
        try{
       // Sentencia DELETE
        $comando = "DELETE FROM " . self::NOMBRE_TABLE .
        " WHERE " . self::ID_CORRIDA . "=?";

        // Preparar la sentencia
        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
        $sentencia->bindParam(1, $id_corridax);

        $sentencia->execute();

        return $sentencia->rowCount();


        }catch(PDOException $e){
            throw new ExcepcionApi(3, $e->getMessage());
        }
    }
}
