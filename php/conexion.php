<?php

class Database {
    private $PDO;
    private $user = "root";
    private $password = "";
    private $server = "mysql:host=localhost; dbname=laespiga";

    function concetarBD() {
        try {
            $this->PDO = new PDO($this->server, $this->user, $this->password);
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
            $sql = $this->PDO->query("select usuarios.id_usuario as ID, rol_usuario.id_rol as Rol, usuarios.contraseña as Contraseña
                                      from usuarios
                                      inner join rol_usuario on usuarios.id_usuario = rol_usuario.id_usuario
                                      where usuarios.username = '$usuario'");
            while($renglon = $sql->fetch(PDO::FETCH_ASSOC)){
                if (password_verify($password, $renglon['Contraseña'])){
                    session_start();
                    $_SESSION['ID'] = $renglon['ID'];
                    $_SESSION['Rol'] = $renglon['Rol'];
                }
                else {
                    echo "Contraseña Invalido";
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
        header("Location: ../vistas/index.php");
    }
}

?>