<?php
require ('./db/DB.php');

// Инициализация
$db = new \App\DB('localhost', 'root', '', 'sample');

$withoutImage = $db->select()
    ->from('movie')
    ->where('movie_id NOT IN (SELECT DISTINCT movie_id FROM pictures)')
    ->all();

$idListWithoutImage = array_map(function ($item){
    return $item->movie_id;
}, $withoutImage);


$withImage = $db->select()
    ->from('movie')
    ->where('movie_id NOT IN ('. implode(',', $idListWithoutImage).')')
    ->all();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kinorium</title>
    <link rel="stylesheet" href="lib/tree/themes/default/style.min.css" />
</head>
<body>

<div id="films">
    <ul>
        <li>фильмы
            <ul>
                <li>фильмы с кадрами
                    <ul>
                        <?php foreach($withImage as $item): ?>
                            <li><?= $item->title ?></li>
                        <?php endforeach ?>
                    </ul>
                </li>
                <li>фильмы без кадров
                    <ul>
                        <?php foreach($withoutImage as $item): ?>
                            <li><?= $item->title ?></li>
                        <?php endforeach ?>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="lib/tree/jstree.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>
