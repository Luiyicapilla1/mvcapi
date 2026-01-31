<?php

namespace Cls\Mvc2app;

use Cls\Mvc2app\Db;
use PDO;

class Car
{
    private $db;

    private $id;
    private $brand;
    private $model;
    private $color;
    private $owner;

    /**
     * @param $db
     * @param $id
     * @param $brand
     * @param $model
     * @param $color
     * @param $owner
     */
    public function __construct($id = 0, $brand = '', $model = '', $color = '', $owner = '')
    {
        $this->db = new Db();
        $this->id = $id;
        $this->brand = $brand;
        $this->model = $model;
        $this->color = $color;
        $this->owner = $owner;
    }

    /**
     * @return int|mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed|string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param mixed|string $brand
     */
    public function setBrand($brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return mixed|string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed|string $model
     */
    public function setModel($model): void
    {
        $this->model = $model;
    }

    /**
     * @return mixed|string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed|string $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
    }

    /**
     * @return mixed|string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed|string $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }

    public function getAll(): array {
        $this->db->query('SELECT * FROM cars');
        $restult = $this->db->registrosAssoc();
        return $restult;
    }

    public function getById(int $id): ?array {
        $this->db->query('SELECT * FROM cars WHERE id = :id');
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        $row = $this->db->registroAssoc();
        return $row ?: null;
    }

    public function create(array $data): bool {
        $this->db->query(
            "INSERT INTO cars (brand, model, color, owner)
         VALUES (:brand, :model, :color, :owner)"
        );
        $this->db->bind(':brand', $data['brand']);
        $this->db->bind(':model', $data['model']);
        $this->db->bind(':color', $data['color']);
        $this->db->bind(':owner', $data['owner']);
        return $this->db->execute();
    }

    public function update(int $id, array $data): bool {
        $this->db->query(
            "UPDATE cars 
         SET brand = :brand, model = :model, color = :color, owner = :owner
         WHERE id = :id"
        );
        $this->db->bind(':brand', $data['brand']);
        $this->db->bind(':model', $data['model']);
        $this->db->bind(':color', $data['color']);
        $this->db->bind(':owner', $data['owner']);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function delete(int $id): bool {
        $this->db->query("DELETE FROM cars WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

}