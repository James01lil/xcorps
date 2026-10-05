<?php
$modeFile = 'mode.txt';
$mode = trim(file_get_contents($modeFile));

if ($mode === 'B') {
    include 'formB.php';
} else {
    include 'formA.php';
}
?>