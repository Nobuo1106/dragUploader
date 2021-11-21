<?php
require_once './../../Db.php';
require_once './../../Config.php';
class Model{
    private $db;
    private static $file_name_length = 24;
    private $random;

    public function __construct() {
        $this->db = new DB;
        $this->random = $this->makeRandomString(self::$file_name_length);
    }

    // ランダム文字列ゲッター
    public function getRandom() {
        return $this->random;
    }

    function saveFilePath($file_name, $save_path) {
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

    private function makeRandomString($length) {
	    return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
    }

    public function createFileNames($files_ext) {
        $random_string = $this->makeRandomstring(self::$file_name_length);
        foreach ($files_ext as $d){
            $file_names[$d] = $random_string . '.' . $d;
        }
        return $file_names;
    }

    public function createDirectory($random) {
        if (!file_exists($random)) {
            mkdir($random, 0777);
        }
        return $random;
    }

    function createFile($path) {
        $file_path =  $path . '/index.php';
        return(touch($file_path));
    }

    function uploadImg($files, $path) {
        $msg = null;
        if(isset($files)){
            for($i = 0; $i < count($files['name']); $i++ ){
                if(is_uploaded_file($files['tmp_name'][$i])){
                    if(move_uploaded_file($files['tmp_name'][$i], $path . $files["name"][$i])){
                        $save_result = $this->saveFilePath($files['name'], $files['path']);
                        if($save_result) {
                            echo $files['org_name'] . $files['path'] . 'データベースに保存しました。';
                        } else {
                            $msg += 'データベースに保存できませんでした。';
                        }
                    }
                } else {
                    $msg += 'データベースに保存できませんでした。';
                }
            }
        } else {
            $msg = 'ファイルが選択されていません。';
        }
        return $msg;
    }

    public function validateImg() {

    }
}
