<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Votante_model extends CI_Model{

    public function get()
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                     from votante")->result_array();

        return $query;
    }

    public function create($data){
        $this->voto = $this->load->database('voto', TRUE);
        $data['clave'] = null;
        $data['correo_enviado'] = 0;
        $data['papeleta'] = 0;

        $query = $this->voto->insert('votante', $data);
        if($query){
            return 'TRUE';
        }
        else{
            return 'FALSE';
        }
    }

    public function verificar($cedula)//verifica si el votante existe
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query= $this->voto->query("SELECT *
                                      FROM votante
                                      where cedula = '$cedula'")->num_rows();
        if ($query == 0) {
            return false;
        }
        else{
            return true;
        }
    }

    public function existeUsuario($cedula, $clave)//comprueba cedula y clave y devuelve al votante
    {
        $this->voto = $this->load->database('voto', TRUE);
        if($clave != NULL) {
            $query = $this->voto->query("SELECT *
                                                FROM   votante
                                                WHERE  cedula = '$cedula'
                                                and clave = '$clave'
                                                and estado = 1;");
            $num = $query->num_rows();
        }
        else{
            $num = 0;
        }
        if ($num > 0) {
            $query = $this->voto->query("SELECT *
                                     from votante
                                     WHERE cedula = '$cedula';")->result_array();
            return $query;
        }
        else{
            return false;
        }
    }

    public function votoUsuario($cedula)//verifica si el votante ya tiene votos
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("select * 
                                        from voto
                                         where cedula_votante = '$cedula';");
        $num = $query->num_rows();
        if ($num > 0)
            return true;
        else{
            return false;
        }
    }

    public function generar()
    {
        $this->voto = $this->load->database('voto', TRUE);
        $num = $this->getnumClaves();
        $chars = "abcdefghijklmnopqrstuvwxyz";
        for ($i = 0; $i < $num; $i++) {
            $password = substr( str_shuffle( $chars ), 0, 8 );
            $query = $this->voto->query("SELECT top(1) *
                                          from votante
                                          where clave is null ;");
            $query = $query->result_array();
            $cedula = $query[0]['cedula'];
            $queryupdate =  $this->voto->query("update votante
                                                    set clave = '$password'
                                                    where cedula = '$cedula'");
        }

        if ($queryupdate)
            return true;
        else{
            return false;
        }
    }

    public function getnumClaves()
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                          from votante
                                          where clave is null");
        $num = $query->num_rows();
        return $num;
    }

    public function getnumCorreos()
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                          from votante
                                          where correo_enviado = 0
                                          and clave is not null");
        $num = $query->num_rows();
        return $num;
    }

    public function getCorreos ()
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT cedula, email, clave
                                          from votante
                                          where correo_enviado = 0" );
        return $query->result_array();
    }

    public function correo($from, $bcc, $mensaje, $subject)
    {
        $this->email->from($from);
        $this->email->bcc($bcc);
        $this->email->subject($subject);
        $this->email->set_mailtype("html");
        $this->email->message($mensaje);
        $envio = $this->email->send();
        if ($envio)
            return true;
        else
            return false;
    }

    public function marcarenviado($cedula)
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("update votante 
                                          set correo_enviado = 1
                                          where cedula = '$cedula'");
        if($query)
        {
            return 'TRUE';
        }
        else
        {
            return 'FALSE';
        }
    }

    public function loadExcel($nombre)
    {
        $this->voto = $this->load->database('voto', TRUE);
        $num = 0;
        $resp = false;
        date_default_timezone_set('America/Bogota');
        $fecha = date("Y-m-d");
        if (($handle = fopen("../documentos/voto/".$nombre.".csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE){
                if($num != 0) {
                    $data[1] = $this->quitar_tildes($data[1]);
                    $data[2] = $this->quitar_tildes($data[2]);
                    $data[3] = $this->quitar_tildes($data[3]);
                    $existe = $this->voto->query("SELECT *
                                                      FROM votante
                                                      where cedula = '$data[0]';")->num_rows();
                    if($existe == 0) {
                        $resp = $this->voto->query("insert into votante
                                             (cedula, nombres, asociacion, email, clave, correo_enviado, papeleta, fecha_creado)
                                       values ('$data[0]','$data[1]','$data[2]','$data[3]',null, 0, 0, '$fecha');");//cambiar test por estudiante
                    }
                }
                $num++;
            }
            fclose($handle);
        }
        if($resp)
            return 'TRUE';
        else
            return 'FALSE';
    }

    public function quitar_tildes($cadena){
        $cadena = utf8_encode($cadena);

        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );

        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena );

        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena );

        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena );

        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena );

        return $cadena;
    }

    function update($cedula, $data)
    {
        $this->voto = $this->load->database('voto', TRUE);
        $this->voto->where('cedula', $cedula);
        $resp = $this->voto->update('votante', $data);
        if($resp)
        {
            return 'TRUE';
        }
        else
        {
            return 'FALSE';
        }
    }

    public function getCedula ($cedula)//retorna el votante por la cedula
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                          from votante
                                          where cedula like '$cedula%'" );
        return $query->result_array();
    }
}