<?php
require_once './../../Model.php';
$model = new Model;
$files = $model->getFile();
$randum_str = md5(uniqid(rand(), true));
?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ドラッグ&ドロップでファイルをアップロード</title>
    <link rel="stylesheet" href="./css/mian.css">
</head>
<body>
<h1>画像アップロード</h1>
<form action="./upload.php" method="post" enctype="multipart/form-data">
    <div id="drop-zone" style="border: 1px solid; padding: 30px;">
        <p>ファイルをドラッグ＆ドロップもしくはファイルを選択ボタンをクリック</p>
        <input type="file" name="file" id="file-input" accept="image/png, image/jpeg, mage/gif">
        <input type="hidden" name="MAX_FILE_SIZE" value="1038576">
    </div>
    <h2>アップロードした画像</h2>
    <div class="preview" id="preview"></div>
    <div class="zoom">
        <div id="zoom-out">
            <button>-</button>
        </div>
        <div id="zoom-in">
            <button>+</button>
        </div>
    </div>
    <div class="container-fluid mx-0">
        <div class="form-group row tooltip">
            <input class="border border-info rounded text-secondary form-control-plaintext col-10" id="copyTarget" type="text" value="おまけ" readonly>
            <button type="button" class="btn btn-info col" id="copy_btn" data-toggle="tooltip" data-placement="top" title="コピーする">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24"><path d="M17,9H7V7H17M17,13H7V11H17M14,17H7V15H14M12,3A1,1 0 0,1 13,4A1,1 0 0,1 12,5A1,1 0 0,1 11,4A1,1 0 0,1 12,3M19,3H14.82C14.4,1.84 13.3,1 12,1C10.7,1 9.6,1.84 9.18,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z" /></svg>
            </button>
            <div class="tooltiptext">
                <span>コピーしました!</span>
            </div>
        </div>
    </div>
    <br>
    <input type="submit" style="margin-top: 50px">
</form>

<script type="text/javascript">
    var dropZone = document.getElementById('drop-zone');
    var preview = document.getElementById('preview');
    var fileInput = document.getElementById('file-input');
    var zoomIn = document.getElementById('zoom-in');
    var zoomOut = document.getElementById('zoom-out');
    var copybtn = document.getElementById('copy_btn');

    dropZone.addEventListener('dragover', function(e) {
        e.stopPropagation();
        e.preventDefault();
        this.style.background = '#e1e7f0';
    }, false);

    dropZone.addEventListener('dragleave', function(e) {
        e.stopPropagation();
        e.preventDefault();
        this.style.background = '#ffffff';
    }, false);

    fileInput.addEventListener('change', function(e) {
        if(validateImage(this.files[0])){
            previewFile(this.files[0]);
        } else {
            event.currentTarget.value = ''
        } 
    });

    function copyFromClipboard() {
        var copyTarget = document.getElementById("copyTarget");
        copyTarget.select();
        document.execCommand("Copy");
    }

    document.addEventListener('click', copyFromClipboard);
    
    dropZone.addEventListener('drop', function(e) {
        e.stopPropagation();
        e.preventDefault();
        this.style.background = '#ffffff'; //背景色を白に戻す
        var files = e.dataTransfer.files; //ドロップしたファイルを取得
        if(validateImage(files[0])){
            if (files.length > 1) return alert('アップロードできるファイルは1つだけです。');
            fileInput.files = files; //inputのvalueをドラッグしたファイルに置き換える。
            previewFile(files[0]);
        } else {
            event.currentTarget.value = ''
        }
    }, false);

    zoomOut.addEventListener('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        imgEl = document.querySelector('.prv-img')
        height = imgEl.clientHeight - 80;
        imgEl.style.height = height + 'px';
    });

    zoomIn.addEventListener('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        e.stopPropagation();
        e.preventDefault();
        imgEl = document.querySelector('.prv-img')
        height = imgEl.clientHeight + 80;
        imgEl.style.height = height + 'px';
    });

    function previewFile(file) {
        var fr = new FileReader();
        fr.readAsDataURL(file);
        fr.onload = function() {
            var img = document.createElement('img');
            img.className = 'prv-img';
            img.setAttribute('src', fr.result);
            preview.innerHTML = '';
            preview.appendChild(img);
        };
    }

    function validateImage(image) {
        // check the type
        var validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (validTypes.indexOf( image.type ) === -1) {
            alert("無効なファイル形式です。");
            return false;
        }

        // check the size
        var maxSizeInBytes = 10e6; // 10MB
        if (image.size > maxSizeInBytes) {
            alert("ファイルの最大サイズは10MBです。");
            return false;
        }
        return true;
    }

function getRndStr(){
  //使用文字の定義
  var str = "abcdefghijklmnopqrstuvwxyz0123456789";
 
  //桁数の定義
  var len = 24;
 
  //ランダムな文字列の生成
  var result = "";
  for(var i=0;i<len;i++){
    result += str.charAt(Math.floor(Math.random() * str.length));
  }
  return result;
}
var tooltip = document.querySelector('.tooltip')

tooltip.addEventListener('click', function() {
  if (this.classList.contains('active')) {
    this.classList.remove('active');
  } else {
    this.classList.add('active');
    setTimeout(function(){
      this.classList.remove('active');
    }.bind(this), 500);
  } 
});

</script>
</body>
</html>