<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use phpqrcode;

class NeController extends Controller
{

	public function qrCode( Request $request ) {
		/*$post_url = $request->get('post_url');
		$qrcode_text = $request->get('qrcode_text');
		$qrcode_x = $request->get('qrcode_x');
		$qrcode_y = $request->get('qrcode_y');*/
        $post_url = public_path().'/'.'qrthree.jpg';
        $qrcode_text = 'https://www.baidu.com';
        $qrcode_x = 247;
        $qrcode_y = 799;
        $img = new \QRcode();

        $errorCorrectionLevel = 'H';
        $qrcodeSize = 250;

        $matrixPointSize = floor($qrcodeSize/37*100)/100 + 0.01;

		$qrcode_name = 'qrcode.png';

        $img->png($qrcode_text, $qrcode_name, $errorCorrectionLevel, $qrcodeSize ,0);
		$backgroundInfo = getimagesize($post_url);
		$backgroundFun = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
		$background = $backgroundFun($post_url);
		$backgroundWidth = imagesx($background);
		$backgroundHeight = imagesy($background);
		$imageRes = imageCreatetruecolor($backgroundWidth, $backgroundHeight);
		$color = imagecolorallocate($imageRes, 0, 0, 0);
		imagefill($imageRes, 0, 0, $color);
		imagecopy($imageRes, $background, 0, 0, 0, 0, $backgroundWidth, $backgroundHeight);


        /*$QR = public_path().'/'.'qrcode.png';//已经生成的原始二维码图
        //将二维码背景变透明
        $resource = imagecreatefrompng($QR);
        @unlink($QR);
        $bgcolor = imagecolorallocate($resource, 255, 255, 255);
        imagefill($resource, 0, 0, $bgcolor);
        imagecolortransparent($resource, $bgcolor);


        imagepng($resource,'qrcode_copy.png');*/
		$code = public_path().'/'.'qrcode.png';
		$src_img = imagecreatefrompng($code);

		$src_w = imagesx($src_img);
		$src_h = imagesy($src_img);
		imagecopy($imageRes, $src_img, $qrcode_x, $qrcode_y, 0, 0, $src_w, $src_h);

		ob_clean();
		header("Content-Type:image/jpeg");
		imagejpeg($imageRes);

		imagedestroy($imageRes);
    }
}
