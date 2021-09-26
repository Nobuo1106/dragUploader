<?php
$file = $_FILES['file'];
$filename = $file['name'];
$tmp_path = $file['tmp_name'];
$file_err = $file['error'];
$file_size = $file['size'];
$upload_dir = '/Applications/MAMP/htdocs/upload/images/';
$save_filename = date('Ymd') . $filename;
$err_msgs = array();

if($file_size > 2048576 || $file_err === 2) {
    array_push($err_msgs, 'ファイルサイズは1MB未満にして下さい。');
}

$allow_ext = array('jpg', 'jpeg', 'png');
$file_ext = pathinfo($filename, PATHINFO_EXTENSION);

if(!in_array(strtolower($file_ext), $allow_ext)){
    array_push($err_msgs, '適切なファイルを添付して下さい');
    echo $file_ext;
}

if(count($err_msgs) === 0){
    if(is_uploaded_file($tmp_path)) {
        if(move_uploaded_file($tmp_path, $upload_dir.$save_filename)){
            echo $filename . $upload_dir . 'ファイルがアップロードされました。';
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

?>