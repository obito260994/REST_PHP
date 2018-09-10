<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once( APPPATH.'/libraries/REST_Controller.php');
use Restserver\libraries\REST_Controller;

class Login extends REST_Controller {

    public function __construct(){
        
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");

        parent:: __construct();
        $this->load->database();

    }

    public function index_post(){

        $data = $this->post();

        if(!isset($data['correo']) OR !isset($data['contrasena'] )){
            $mensaje = array(
                'error' => TRUE ,
                'mensaje' =>'correo o contrasena incorrecto'
            );
            $this->response($mensaje, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
        //tenemos correo y contraseña en un post
        $condiciones = array(
        'correo' => $data['correo'],
        'contrasena' => $data['contrasena']
        );

        $query = $this->db->get_where( 'login' , $condiciones );
        $usuario = $query->row();

        if(!isset($usuario)){
        $respuesta = array(
        'error' => TRUE,
        'mensaje' => 'usuarrio y/o contrasena incorrectos'
        );

        $this->response($respuesta);
        return;
        }

        //si tenemos usuario y contraseña validos

        $token = bin2hex(openssl_random_pseudo_bytes(20));
        $tokex = hash('ripemd160', $data['correo']);
        //limpiar query
        $this->db->reset_query();

        //actuaizar token
        /*
        $actualizar_token = array(
                 'token'=> $token
        );*/

        //condicion
        $this->db->where('id' , $usuario->id);
        
        $hecho = $this->db->update('login' , array(
            'token'=> $token
        ));
        $respuesta = array(
                        'error'=> 'FALSE',
                        'token'=> $token,
                        'id_usuario' => $usuario->id    
        );
        $this->response($respuesta);

    }

    
}