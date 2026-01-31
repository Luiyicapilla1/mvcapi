<?php
namespace lgc\mvc2app;

/**
Mapear URL desde el navegador
1- controlador
2- método
3- parámetro

formato de la url: BASE_DIR/controlador/metodo/parametro

 */
class Core
{
    protected $controladorActual = 'apicar';
    protected $metodoActual = 'cars';
    protected array $parametros = [];

    public function __construct()
    {
        $url = $this->getUrl() ?? [];

        // 1) Controlador desde URL
        $controlador = $url[0] ?? '';
        $controlador = strtok($controlador, '?');
        $controlador = trim($controlador);

        if ($controlador !== '') {
            $controladorClase = ucfirst(strtolower($controlador)); // paginas -> Paginas
            $fqcn = __NAMESPACE__ . '\\' . $controladorClase;

            // class_exists() disparará el autoload de Composer
            if (class_exists($fqcn)) {
                $this->controladorActual = $controladorClase;
                unset($url[0]);
            }
        }

        // 2) Instanciar controlador (con namespace)
        $fqcnControlador = __NAMESPACE__ . '\\' . $this->controladorActual;
        $this->controladorActual = new $fqcnControlador();

        // 3) Método
        $metodo = $url[1] ?? null;

        if ($metodo && method_exists($this->controladorActual, $metodo)) {
            $this->metodoActual = $metodo;
            unset($url[1]);
        } else {
            // Si no hay método válido, usar el método por defecto
            $metodo = $this->metodoActual;
        }

        // 4) Parámetros
        unset($url[0]); // quitar controlador SIEMPRE
        $this->parametros = array_values($url);

        // Convertir números a int
        $this->parametros = array_map(fn($p) => is_numeric($p) ? (int)$p : $p, $this->parametros);

        // 5) Ejecutar
        call_user_func_array([$this->controladorActual, $this->metodoActual], $this->parametros);
    }

    public function getUrl(): ?array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return null;
    }
}