<?php

class Database {
    private $PDO;
    private $user = "root";
    private $password = "laespiga2190";
    private $server = "mysql:host=localhost; dbname=LAESPIGA;charset=utf8mb4";

    function conectarBD() {
        try {
            $this->PDO = new PDO($this->server, $this->user, $this->password);
            
            $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function desconectarBD() {
        try {
            $this->PDO = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function selectConsulta ($consulta) {
        try {
            $sql = $this->PDO->query($consulta);
            $resultado = $sql->fetchAll(PDO::FETCH_OBJ);
            return $resultado;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function ejecutar ($consulta) {
        try {
            $this->PDO->query($consulta);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    function verifica($usuario, $password){
        try{
            $sql = $this->PDO->query("call BuscarUsuario('$usuario', @mensaje)");
            $renglon = $sql->fetchAll(PDO::FETCH_OBJ);
            if (count($renglon) == 0) {
                $consulta = $this->PDO->query("SELECT @mensaje as resultado");
                $mensaje = $consulta->fetchAll(PDO::FETCH_OBJ);
                echo $mensaje[0]->resultado;
            }
            else {
                foreach ($renglon as $fila) {
                    if (password_verify($password, $fila->Contraseña)){
                        session_start();
                        $_SESSION['ID'] = $fila->ID;
                        $_SESSION['Rol'] = $fila->Rol;
                        echo $fila->Rol;
                    }
                    else {
                        echo "Contraseña Invalido";
                    }
                }
            }
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    } 
    function CerrarSeccion(){
        session_start();
        session_destroy();
        header("Location: ../index.php");
    }
}

?>