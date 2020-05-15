<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use phpqrcode;

class PosterController extends Controller
{
    /**
     * 实现海报
     *
     * @param Request $request
     */
    public function getPoster(Request $request){
        $postUrl = $request->get('postUrl');
        $qrcodeText = $request->get('qrcodeText');
        $qrcodeX = $request->get('qrcodeX');
        $qrcodeY = $request->get('qrcodeY');
        $qrcodeSize = $request->get('qrcodeSize');
        $errorCorrectionLevel = 'H';
        $img = new \QRcode();
        $time = microtime(true)*10000;
        $qrcodeName = 'qrcode'.$time.'.png';
        $img->png($qrcodeText, $qrcodeName, $errorCorrectionLevel, $qrcodeSize, 0);
        /* 处理海报底图。*/
        $backgroundInfo = getimagesize($postUrl);
        $backgroundFunc = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
        $background = $backgroundFunc($postUrl);
        $backgroundWidth = imagesx($background);
        $backgroundHeight = imagesy($background);
        $imageRes = imagecreatetruecolor($backgroundWidth, $backgroundHeight);
        $color = imagecolorallocate($imageRes, 0, 0, 0);
        imagefill($imageRes, 0, 0, $color);
        imagecopy($imageRes, $background, 0, 0, 0, 0, $backgroundWidth, $backgroundHeight);
        /* 处理二维码。*/
        $qrcode = public_path().'/'.$qrcodeName;
        $srcImg = imagecreatefrompng($qrcode);
        $srcW = imagesx($srcImg);
        $srcH = imagesy($srcImg);
        imagecopy($imageRes, $srcImg, $qrcodeX, $qrcodeY, 0, 0, $srcW, $srcH);
        ob_clean();
        header("Content-type:image/jpeg");
        imagejpeg($imageRes);
        imagedestroy($imageRes);
    }
}
