<?php
$file = $_GET['t'];
if (preg_match('/(js|css|jpg|png|jpge|ico|gif)$/i', $file)) {
    header('content-type:');
    readfile(dirname(__FILE__) . $file);
}
else{
    echo 'dont be foo';
}