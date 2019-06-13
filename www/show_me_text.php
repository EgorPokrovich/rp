<?php

    error_reporting(0);
    session_start();

    require_once ('main_paige_check.php');

    $_SESSION['name'] =  $_POST['login'];
    $_SESSION['secoundName'] = $_POST['password'];
    $_SESSION['them_url'] = $_GET['action'];
    $_SESSION['them_name'] = $_GET['gman123'];
    $_SESSION['gname'] =  $_POST['gname'];
    $_SESSION['glink'] = $_POST['glink'];

$them_name_for_conv = $_GET['gnam123'];
    $regexp_for_delete = '!\?!si';
    $them_name_for_conv = preg_replace($regexp_for_delete, '',$them_name_for_conv);

    $url = [
        0 => 'http://roleplay.lainurol.com/'.$_GET['action'],
        1 => $them_name_for_conv
    ];

    //$text = read_them($url, $logCredits);
    $text = get_full_them($url, $logCredits);
    $regexp_for_delete = '!\?!si';
    $url_them[1] = preg_replace($regexp_for_delete, '', $url_them[1]);
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
<body>
<a href="<?php echo '/files/' . $url[1] . '.txt'; ?>" class="btn btn-warning btn-lg btn-block">Открыть файл</a>
</body>
</html>
<?php
require_once ('show_games_table.php');
//require_once ('clear_files.php');
//unlink ('./' . gearbest_rp.txt');
?>
