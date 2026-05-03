<?php
class Assignment {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAll(){
        return $this->pdo->query("SELECT * FROM assignments")->fetchAll();
    }
}