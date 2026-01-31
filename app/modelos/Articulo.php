<?php
namespace lgc\mvc2app;
use lgc\mvc2app\Controlador;
use lgc\mvc2app\Db;

class Articulo{ 
    private $bd;

    public function __construct()
    {
        $this->bd = new Db();
    }

    public function obtenerArticulos(){
        $this->bd->query("SELECT * FROM articulos");
        return $this->bd->registrosAssoc();
    }

    public function getById(int $id): ?array {
        $this->bd->query('SELECT * FROM articulos WHERE id_articulo = :id');
        $this->bd->bind(':id', $id, \PDO::PARAM_INT);
        $row = $this->bd->registroAssoc();
        return $row ?: null;
    }

    public function create(array $data): bool {
        $this->bd->query(
            "INSERT INTO articulos (titulo) VALUES (:titulo)"
        );
        $this->bd->bind(':titulo', $data['titulo']);
        return $this->bd->execute();
    }

    public function update(int $id, array $data): bool {
        $this->bd->query("UPDATE articulos SET titulo = :titulo WHERE id_articulo = :id");
        $this->bd->bind(':titulo', $data['titulo']);
        $this->bd->bind(':id', $id);
        return $this->bd->execute();
    }

    public function delete(int $id): bool {
        $this->bd->query("DELETE FROM articulos WHERE id_articulo = :id");
        $this->bd->bind(':id', $id);
        return $this->bd->execute();
    }
}
