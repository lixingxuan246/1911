<?php

namespace App\Http\Controllers\Pc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RedController extends Controller
{
    public function info()
    {
        phpinfo();
    }
    public function test()
    {
        header('Content-Type: image/png');

        // Create the image
        $im = imagecreatetruecolor(100, 30);

        // Create some colors
        $white = imagecolorallocate($im, 255, 255, 255);
        $grey = imagecolorallocate($im, 128, 128, 128);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 399, 29, $white);

        // The text to draw
        $text = ''.rand(1000,9999);
        // Replace path by your own font path
        $font = storage_path().'/calibril.ttf';

        // Add some shadow to the text
        $i = 0;
        while($i<strlen($text)){
            imageline($im,rand(0,100),rand(0,25),rand(90,100),rand(10,25),$grey);
            imagettftext($im,20,rand(-15,15),11+20*$i,21,$black,$font,$text[$i]);
            $i++;
        }
        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($im);
        imagedestroy($im);
        exit;
    }
}
