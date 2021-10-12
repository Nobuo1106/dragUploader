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
    <link rel="stylesheet" href="main.css">
    
</head>
<body>
    <p>hello</p>
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
    <div>
        <?php foreach ($files as $file): ?>
            <img src="<?php echo "{$file['path']}"; ?>" alt="">
        <?php endforeach; ?>
    </div>
    <div>
        <textarea id="copy">hello world</textarea>
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

    var copy = document.getElementById('copy')

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
        height = imgEl.clientHeight - 40;
        imgEl.style.height = height + 'px';
    });

    zoomIn.addEventListener('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        e.stopPropagation();
        e.preventDefault();
        imgEl = document.querySelector('.prv-img')
        height = imgEl.clientHeight + 40;
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

</script>
</body>
</html>