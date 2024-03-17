<?php
require __DIR__. '/vendor/autoload.php';

use App\FilesDifference;

$fileDiff = new FilesDifference();
$fileDiff->compare(file_get_contents('files/File1.txt'), file_get_contents('files/File2.txt'), true);

echo $fileDiff->getDifference();