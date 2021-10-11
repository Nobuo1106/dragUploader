<?php
require_once './../../db.php';
class Model{
    private $db;

    public function __construct() {
        $this->db = new DB;
    }

    function SaveFilePath($file_name, $save_path) {
        $result = false;
        try{
            $sql = 'INSERT INTO files(name, path) VALUE(:file_name, :save_path)';
            $stmt = $this->db->dbc()->prepare($sql);
            $stmt->bindParam('file_name', $file_name, PDO::PARAM_STR);
            $stmt->bindParam('save_path', $save_path, PDO::PARAM_STR);
            $result = $stmt->execute();
            return $result;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return $result;
        }
    }

    function getFile() {
        $sql = "select * from files";
        $stmt = $this->db->dbc()->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        return $data;
    }
}