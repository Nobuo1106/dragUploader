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
            <input type="file" name="image[]" id="file-input" accept="image/png, image/jpeg, mage/gif">
            <input type="hidden" name="MAX_FILE_SIZE" value="1038576">
        </div>
        <h2>アップロードした画像</h2>
        <div class="preview" id="preview"></div>
        <div class="container-fluid mx-0">
            <div class="form-group row tooltip">
                <input class="border border-info rounded text-secondary form-control-plaintext col-10" id="copyTarget" type="text" value="おまけ" readonly>
                <button type="button" class="btn btn-info col" id="copy_btn" data-toggle="tooltip" data-placement="top" title="コピーする">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M17,9H7V7H17M17,13H7V11H17M14,17H7V15H14M12,3A1,1 0 0,1 13,4A1,1 0 0,1 12,5A1,1 0 0,1 11,4A1,1 0 0,1 12,3M19,3H14.82C14.4,1.84 13.3,1 12,1C10.7,1 9.6,1.84 9.18,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z" />
                    </svg>
                </button>
                <div class="tooltiptext">
                    <span>コピーしました!</span>
                </div>
            </div>
        </div>
        <br>
        <input type="submit" style="margin-top: 50px">
    </form>
</body>
<script type="text/javascript">
    var dropZone = document.getElementById('drop-zone');
    var preview = document.getElementById('preview');
    var fileInput = document.getElementById('file-input');
    var copybtn = document.getElementById('copy_btn');
    const dt = new DataTransfer();

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
        if (validateImage(this.files[0])) {
            previewFiles(this.files[0]);
        } else {
            event.currentTarget.value = ''
        }
    });

    function copyFromClipboard() {
        var copyTarget = document.getElementById("copyTarget");
        copyTarget.select();
        document.execCommand("Copy");
    }

    //clipboard
    document.addEventListener('click', copyFromClipboard);

    dropZone.addEventListener('drop', function(e) {
        e.stopPropagation();
        e.preventDefault();
        this.style.background = '#ffffff'; //背景色を白に戻す
        var droppedFiles = e.dataTransfer.files; //ドロップしたファイルを取得
        if (validateImage(droppedFiles[0])) {
            if (fileInput.files.length === 0) {
                fileInput.files = droppedFiles; //inputのvalueをドラッグしたファイルに置き換える。
            } else {
                const inputFiles = getFiles(fileInput)
                dt.items.add(droppedFiles[0]);
                setFiles(fileInput, inputFiles, droppedFiles[0])
            }
            previewFiles(fileInput.files);
        } else {
            event.currentTarget.value = ''
        }
    }, false);

    function previewFiles(files) {
        let key = 0;
        let field = document.getElementById('preview');
        while (field.firstChild) {
            while (field.firstChild) {
                field.removeChild(field.firstChild);
            }
        }
        for (i = 0; i < files.length; i++) {
            var fileReader = new FileReader();
            fileReader.onload = (function(e) {
                var figure = document.createElement('figure');
                var rmBtn = document.createElement('input');
                var zoomOutBtn = document.createElement('button');
                var zoomInBtn = document.createElement('button');
                var img = new Image();
                img.src = e.target.result;
                img.classList.add = 'prev-img';
                rmBtn.type = 'button';
                rmBtn.name = key;
                rmBtn.value = '削除';
                zoomOutBtn.id = 'zoom-out' + key;
                zoomInBtn.id = 'zoom-in' + key;
                zoomInBtn.innerHTML = '-';
                zoomOutBtn.innerHTML = '-';
                zoomInBtn.innerHTML = '+';
                rmBtn.onclick = (function() {
                    var element = document.getElementById("figure-" + String(rmBtn.name)).remove();
                });
                figure.setAttribute('id', 'figure-' + key);
                img.setAttribute('class', 'prv-img');
                img.setAttribute('id', 'img' + key);
                figure.appendChild(img);
                figure.appendChild(zoomInBtn);
                figure.appendChild(zoomOutBtn);
                figure.appendChild(rmBtn);
                field.appendChild(figure);
                zoomInBtn.addEventListener('click', {img: img, handleEvent: zoomIn});
                zoomOutBtn.addEventListener('click', {img: img, handleEvent: zoomOut});
                rmBtn.onclick = function(e) {
                    console.log(fileInput.files);
                }
                key++;
            });
            fileReader.readAsDataURL(files[i]);
        }
    }

    function validateImage(image) {
        var validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (validTypes.indexOf(image.type) === -1) {
            alert("無効なファイル形式です。");
            return false;
        }
        var maxSizeInBytes = 10e6; // 10MB
        if (image.size > maxSizeInBytes) {
            alert("ファイルの最大サイズは10MBです。");
            return false;
        }
        return true;
    }

    var tooltip = document.querySelector('.tooltip')

    tooltip.addEventListener('click', function() {
        if (this.classList.contains('active')) {
            this.classList.remove('active');
        } else {
            this.classList.add('active');
            setTimeout(function() {
                this.classList.remove('active');
            }.bind(this), 500);
        }
    });

    function getFiles(input) {
        const files = new Array(input.files.length)
        for (let i = 0; i < input.files.length; i++)
            files[i] = input.files.item(i)
        return files
    }

    function setFiles(input, inputFiles, droppedFiles) {
        const dataTransfer = new DataTransfer()
        for (const inputFile of inputFiles) {
            dataTransfer.items.add(inputFile);
        }
        dataTransfer.items.add(droppedFiles);
        input.files = dataTransfer.files;
    }

    function zoomOut(e) {
        e.stopPropagation();
        e.preventDefault();
        console.log(e.currentTarget);
        console.log(this.img);
        resize = this.img.clientHeight - 80;
        this.img.style.height = resize + 'px';
        this.img.style.width = resize + 'px';
    }

    function zoomIn(e) {
        e.stopPropagation();
        e.preventDefault();
        console.log(e.currentTarget);
        console.log(this.img);
        resize = this.img.clientHeight + 80;
        this.img.style.height = resize + 'px';
        this.img.style.width = resize + 'px';
    }
</script>

</html>