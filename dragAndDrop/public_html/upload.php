<?php
require_once './../../Model.php';
$model = new Model;
$file = $_FILES['file'];
$file_name = $file['name'];
$tmp_path = $file['tmp_name'];
$file_err = $file['error'];
$file_size = $file['size'];
$upload_dir = 'images/';
$now = new DateTime();
$save_file_name =  $now->format('YmdHis'). $file_name;
$err_msgs = array();
$save_path = $upload_dir . $save_file_name;

$f = $d;
if($file_size > 10048576 || $file_err === 2) {
    array_push($err_msgs, 'ファイルサイズは10MB未満にして下さい。');
}

$allow_ext = array('jpg', 'jpeg', 'png', 'gif');
$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

if(!in_array(strtolower($file_ext), $allow_ext)){
    array_push($err_msgs, '適切なファイルを添付して下さい');
    echo $file_ext;
}

if(count($err_msgs) === 0){
    if(is_uploaded_file($tmp_path)) {
        if(move_uploaded_file($tmp_path, $save_path)){
            $result = $model->SaveFilePath($save_file_name, $save_path);
            if($result){
                echo $file_name . $upload_dir . 'データベースに保存しました。';
            } else {
                echo 'データベースに保存できませんでした。';
            }
        } else {
            array_push($err_msgs, 'ファイルアップロードが失敗しました。');
        }
    } else {
        echo 'ファイルが選択されていません。';
        echo '<br>';
    }
} else {
    foreach($err_msgs as $msg) {
        echo $msg;
        echo '<br>';
    }
}

$files = $model->getFile();
?>
