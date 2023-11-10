<?php

$folders;
$files;


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Папки и файлы</title>
    <link rel="stylesheet" href="css/style.css">

    <script src="js/folderOpener.js"></script>
</head>
<body>

<?php foreach ($folders as $folder) {?>
<div class="folder<?=$folder['IS_EMPTY'] === '1'? '' : '-full'?>">
    <p class="folder-name" onclick="openFolder(this.parentElement, <?=$folder["ID"]?>)"><?=$folder["_NAME"]?></p>
</div>
<?php }?>

<div class="form-add">Создать
    <form class="create-form" target="/phptree" method="post">
        <input type="hidden" name="method" value="create">
        <input type="hidden" name="parentId" value="0">
        <select name="which">
            <option selected value="folder">Папку</option>
            <option value="file">Файл</option>
            </select>
        <input type="text" placeholder="имя" name="name">

        <button type="button" onclick="addNew(this)">Создать</button>
        </form>
    </div>
</body>
</html>





