<?php
require_once ('main_paige_check.php');
$ec=$_SESSION['name'];
$eg=$_SESSION['secoundName'];
error_reporting(null);

$link = 'test.php?action=42&name=egor&secoundName=egor2';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['action'] === '42') {
//        echo $_GET['name'];
//        echo $_GET['secoundName'];

        $_SESSION['name'] =  $_GET['name'];
        $_SESSION['secoundName'] = $_GET['secoundName'];
    }
}

echo 'Seesion ' . $_SESSION['name'] . '<br>';
echo 'Seesion ' . $_SESSION['secoundName'];
?>

<!doctype html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body><label for="basic-url">Input url of chosen them or select them from table below</label>
<form class="form-signin" action="target_link.php" method="post">
    <input type="text" id="gname" name="gname" class="form-control" placeholder="them name (for download)" required autofocus>
    <input type="url" id="glink" name="glink" class="form-control" placeholder="game URL" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Open them</button>
</form>
<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Them</th>
        <th scope="col">Game</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th scope="row">1</th>
        <td><a href="<?php echo 'show_me_text.php?action=' . $text_elementsLG[0]; ?>" class="badge badge-primary"><?php echo $text_elementsLG[1];?></a></td>
        <td><?php echo $text_elementsLG[3]?></td>
    </tr>
    <tr>
        <th scope="row">2</th>
        <td><a href="<?php echo 'show_me_text.php?action=' . $text_elementsLG[4]; ?>" class="badge badge-primary"><?php echo $text_elementsLG[5];?></a></td>
        <td><?php echo $text_elementsLG[7];?></td>
    </tr>
    <tr>
        <th scope="row">3</th>
        <td><a href="<?php echo 'show_me_text.php?action=' . $text_elementsLG[8]; ?>" class="badge badge-primary"><?php echo $text_elementsLG[9];?></a></td>
        <td><?php echo $text_elementsLG[11];?></td>
    </tr>
    <tr>
        <th scope="row">4</th>
        <td><a href="<?php echo 'show_me_text.php?action=' . $text_elementsLG[12]; ?>" class="badge badge-primary"><?php echo $text_elementsLG[13];?></a></td>
        <td><?php echo $text_elementsLG[15]?></td>
    </tr>
    <tr>
        <th scope="row">5</th>
        <td><a href="<?php echo 'show_me_text.php?action=' . $text_elementsLG[16]; ?>" class="badge badge-primary"><?php echo $text_elementsLG[17];?></a></td>
        <td><?php echo $text_elementsLG[19];?></td>
    </tr>
    <tr>
        <th scope="row">6</th>
        <td><a href="<?php echo 'show_me_text.php?action=' . $text_elementsLG[20]; ?>" class="badge badge-primary"><?php echo $text_elementsLG[21];?></a></td>
        <td><?php echo $text_elementsLG[23];?></td>
    </tr>
    <tr>
        <th scope="row">7</th>
        <td><a href="<?php echo 'show_me_text.php?action=' . $text_elementsLG[24]; ?>" class="badge badge-primary"><?php echo $text_elementsLG[25];?></a></td>
        <td><?php echo $text_elementsLG[27]?></td>
    </tr>
    <tr>
        <th scope="row">8</th>
        <td><a href="<?php echo 'show_me_text.php?action=' . $text_elementsLG[28]; ?>" class="badge badge-primary"><?php echo $text_elementsLG[29];?></a></td>
        <td><?php echo $text_elementsLG[31];?></td>
    </tr>
    <tr>
        <th scope="row">9</th>
        <td><a href="<?php echo 'show_me_text.php?action=' . $text_elementsLG[32]; ?>" class="badge badge-primary"><?php echo $text_elementsLG[33];?></a></td>
        <td><?php echo $text_elementsLG[35];?></td>
    </tr>
    <tr>
        <th scope="row">10</th>
        <td><a href="<?php echo 'show_me_text.php?action=' . $text_elementsLG[36]; ?>" class="badge badge-primary"><?php echo $text_elementsLG[37];?></a></td>
        <td><?php echo $text_elementsLG[39];?></td>
    </tr>
    </tbody>
</table>
</body>
</html>
