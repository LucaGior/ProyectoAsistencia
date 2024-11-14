<?php

namespace app\controllers;
use app\models\ViewsModel;

class ViewsController extends ViewsModel{

    /*---------- Controlador obtener vistas ----------*/
    public function obtenerVistasControlador($vista){
        if($vista!=""){
            $respuesta=$this->obtenerVistasModelo($vista);
        }else{
            $respuesta="login";
        }
        return $respuesta;
    }
}