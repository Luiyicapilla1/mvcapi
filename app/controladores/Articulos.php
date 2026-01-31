<?php
namespace Cls\Mvc2app;

use Cls\Mvc2app\Controlador;

    class Articulos extends Controlador{

        public function __construct(){
            $this->modelo = $this->modelo('articulo');
            //echo 'Controlador páginas cargado'.'<br>';
            $this->vista = 'index'; //nombre de la vista por defecto, lo normal es que el servidor la asigne por defecto.
            $this->datos = ['titulo' => 'Articulos'];

        }

        public function index(){

            $articulos = $this->modelo->obtenerArticulos();
            $this->datos += [
                'articulos' => $articulos,
            ];

            $this->vista('paginas/ejemplo-inicio', $this->datos);
        }
        public function articulo(){
            $this->vista('paginas/articulo');
        }

        public function actualizar($num_registro){
            echo $num_registro;
        }
        

    }