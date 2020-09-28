<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuariosistema_model extends CI_Model{

    public function existeUsuario($usuario)//si existe el usuario
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                            FROM   usuario_sistema
                                            WHERE  usuario = '$usuario'");
        $num = $query->num_rows();
        if ($num > 0)
            return true;
        else{
            return false;
        }
    }

    public function get()
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT usuario, nombre, id_rol, rol.id rol_id, rol.descripcion rol_descripcion
                                     from usuario_sistema 
                                     inner join rol on rol.id = usuario_sistema.id_rol")->result_array();

        return $query;
    }

    public function getRol($usuario)//retorna el rol del usuario
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT id_rol
                                     from usuario_sistema
                                     where usuario = '$usuario'")->result_array();
        return $query;
    }


    public function verificar($usuario)
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                          from usuario_sistema
                                          where usuario = '$usuario'");
        $num = $query->num_rows();
        if ($num == 0) {
            return true;
        }
        else{
            return false;
        }
    }

    public function delete($usuario)
    {
        $this->voto = $this->load->database('voto', TRUE);
        $this->voto->where('usuario', $usuario);
        $query = $this->voto->delete('usuario_sistema');
        if($query)
            return 'TRUE';
        else
            return 'FALSE';
    }

    function add($data)//inserta un usuario del sistema nuevo
    {
        $this->votacion = $this->load->database('voto', TRUE);
        $resp = $this->votacion->insert('usuario_sistema', $data);
        if($resp)
            return 'TRUE';
        else
            return 'FALSE';
    }

    function update($usuario, $data)
    {
        $this->votacion = $this->load->database('voto', TRUE);
        $this->votacion->where('usuario', $usuario);
        $resp = $this->votacion->update('usuario_sistema', $data);
        if($resp)
        {
            return 'TRUE';
        }
        else
        {
            return 'FALSE';
        }
    }
}