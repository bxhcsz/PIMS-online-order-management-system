<?php 
session_start();
ob_start();
for($i=0;$i<4;$i++){
$rand.=dechex(rand(0,15));
}
$_SESSION["yan"]=$rand;
$im=imagecreatetruecolor(70,20);
$bg=imagecolorallocate($im,185,185,185);
imagefill($im,10,10,$bg);
$zi=imagecolorallocate($im,0,0,0); 
imagettftext($im,12,rand(-4,4),10,15,$zi,'SIMYOU.TTF',$rand);
//增加干扰线
for($j=0;$j<3;$j++){
$line=imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
imageline($im,rand(0,50),rand(0,20),rand(0,50),rand(0,20),$line);
}
//增加噪点
$poter=imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255)); 
for($k=0;$k<100;$k++){
imagesetpixel($im,rand(0,70),rand(0,20),$poter);
}
header("Content-type: image/jpeg");
imagejpeg($im);
?>



