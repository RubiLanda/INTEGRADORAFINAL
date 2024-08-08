<?php

class Database {
    private $PDO;
    private $user = "root";
    private $password = "";
    private $server = "mysql:host=localhost; dbname=LAESPIGA";

    function conectarBD() {
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
            $sql = $this->PDO->query("SELECT USUARIOS.id_usuario AS ID, ROL_USUARIO.id_rol AS Rol, USUARIOS.contrasena AS Contrasena
                                      FROM USUARIOS
                                      inner join ROL_USUARIO ON USUARIOS.id_usuario = ROL_USUARIO.id_usuario
                                      WHERE USUARIOS.username = '$usuario'");
            while($renglon = $sql->fetch(PDO::FETCH_ASSOC)){
                if (password_verify($password, $renglon['Contrasena'])){
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
        header("Location: ../index.php");
    }
}

?>