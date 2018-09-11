<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once( APPPATH.'/libraries/REST_Controller.php');
use Restserver\libraries\REST_Controller;

class Pedidos extends REST_Controller {

    public function __construct(){
        
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");

        parent:: __construct();
        $this->load->database();

    }
    public function realizar_orden_post($token=0, $id_usuario=0){
        $data = $this->post();
        if($token =="0" || $id_usuario =="0" ){
            $respuesta = array(
                        'error' => TRUE,
                        'mensaje' =>'Falta token y/o  id de usuario'
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        if (!isset($data["items"])|| strlen("item") == 0) {
            $respuesta = array(
                'error' => TRUE,
                'mensaje' =>'Falta los items en el post'
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            return; 

        }

        $condiciones  = array(
                        'id' => $id_usuario,
                        'token'=>$token
        );
        $this->db->where($condiciones);
        $query = $this->db->get('login');
        $existe = $query->row();

        if(!$existe){
            $respuesta = array(
                        'error'=>TRUE,
                        'respuesta'=>'usuario y/o token incorrecto'
            );
        $this->response($respuesta); 

        }

    }
}