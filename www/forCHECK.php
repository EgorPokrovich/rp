<?php
require_once 'main_paige_check.php';
echo "files";
$files_names = scandir ('./files' );
echo "not files";
$files_names = scandir ('./' );
echo_arr($files_names);