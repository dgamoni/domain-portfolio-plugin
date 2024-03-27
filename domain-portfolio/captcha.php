<?php
// Filename: captcha.php
// Created by: Mitchell Bundy
// Last Edited by: Mitchell Bundy
// Last Edited: 25/01/2008
// www.borghunter.com
session_start();
$md5 = md5(md5($_GET['rand']));
$string = substr($md5,1,5);

$captcha = imagecreatefrompng("images/captcha.png");

$black = imagecolorallocate($captcha, 0, 0, 0);
$line = imagecolorallocate($captcha,233,239,239);

imageline($captcha,0,0,39,29,$line);
imageline($captcha,40,0,64,29,$line);

imagestring($captcha, 5, 20, 10, $string, $black);

header("Content-type: image/png");
imagepng($captcha);
?>