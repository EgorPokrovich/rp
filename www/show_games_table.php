<?php
session_start();

$_SESSION['name'] =  $_POST['login'];
$_SESSION['secoundName'] = $_POST['password'];
$_SESSION['them_url'] = $_GET['action'];
$_SESSION['them_name'] = $_GET['gman123'];
$_SESSION['gname'] =  $_POST['gname'];
$_SESSION['glink'] = $_POST['glink'];
//$_SESSION['table_elem'] = $text_elementsLG;
$text_elementsLG = $_SESSION['table_elem'];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body><label for="basic-url">Введите ссылку на игровую тему или выберите из таблицы "последних" игр.</label>
<form class="form-signin" action="target_link.php" method="post">
    <input type="url" id="glink" name="glink" class="form-control"  pattern="(.*?)(roleplay.lainurol)(.*?)" placeholder="ссылка на топик" required>
    <input type="text" id="gname" name="gname" class="form-control" placeholder="название темы (для скачивания)" required autofocus>
    <button class="btn btn-lg btn-success btn-block" type="submit">Выгрузить выбранный топик</button>
</form>
<table class="table">
    <thead>
    <tr>
        <th scope="col">Топик</th>
        <th scope="col">Игра</th>
    </tr>
    </thead>

    <?php

     echo '<tbody>';
    for ($i=0; $i<40; $i+=4)
    {
        echo '<tr>';
        echo '    <td><a href=show_me_text.php?action=' . $text_elementsLG[$i] . '&gnam123=' . quotemeta($text_elementsLG[$i + 1]) . ' class="badge badge-primary">' . $text_elementsLG[$i + 1] . '</a></td>';
        echo '  <td>' . $text_elementsLG[$i + 3] . '</td>';
        echo '</tr>';
    }
    ?>

    </tbody>
</table>
</body>