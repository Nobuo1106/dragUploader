<?php
require_once './../../Model.php';
require_once './../../Config.php';

const FILE_NAME_LEGTH = 24;
$model = new Model;
$file = $_FILES['image'];
$file['org_name'] = $file['name'];
$tmp_path  = $file['tmp_name'];
$file_err  = $file['error'];
$file_size = $file['size'];
$err_msgs = array();
$allow_ext = array('jpg', 'jpeg', 'png', 'gif');
$img_ext = pathinfo($file['org_name'], PATHINFO_EXTENSION);

$random = $model->getRandom();
$create_directory_result = $model->createDirectory($random);
$viewer_file_path = dirname(__FILE__) .'/'. $random;
$create_file_result = $model->createFile($viewer_file_path);
$msg = $model->uploadImg($file, $viewer_file_path);

if($file_size > 10048576 || $file_err === 2) {
    array_push($err_msgs, 'ファイルサイズは10MB未満にして下さい。');
}

if(!in_array(strtolower($img_ext), $allow_ext)){
    array_push($err_msgs, '適切なファイルを添付して下さい');
    echo $img_ext;
}

if(isset($upload_err_msg)){
    $err_msgs = array_merge($err_msgs, $upload_err_msg);
}

if(is_null($err_msgs)) {
    header('Location:' . $create_name); 
}

$files = $model->getFile();
?>
