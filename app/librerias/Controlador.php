<?php
namespace lgc\mvc2app;

class Controlador
{
    protected ?string $vista = null;
    protected array $datos = [];
    protected mixed $modelo = null;

    public function modelo(string $modelo)
    {
        $modelo = ucfirst(strtolower($modelo));
        $ruta = RUTA_APP . '/modelos/' . $modelo . '.php';

        if (file_exists($ruta)) {
            require_once $ruta;
            $fqcn = __NAMESPACE__ . '\\' . $modelo;
            return new $fqcn();
        }

        die("El modelo $modelo no existe");
    }

    public function vista(string $vista, array $datos = []): void
    {
        $ruta = RUTA_APP . '/vistas/' . $vista . '.php';
        if (file_exists($ruta)) {
            require_once $ruta;
        } else {
            die("La vista $vista no existe");
        }
    }
}