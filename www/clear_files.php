<?php
$files_names = scandir ('./files' );
foreach ($files_names as $i)
{
    unlink('./files/' . $i);
}