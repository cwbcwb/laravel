<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use phpqrcode;

class PosterController extends Controller
{
    public function getPoster(Request $request){
        $postUrl = $request->get('postUrl');
        $qrcodeText = $request->get('qrcodeText');
        $qrcodeX = $request->get('qrcodeX');
        $qrcodeY = $request->get('qrcodeY');
        $qrcodeSize = $request->get('qrcodeSize');
        $img = new \QRcode();
        $errorCorrectionLevel = 'L';
        $matrixPointSize = floor($qrcodeSize/37*100)/100 + 0.01;
        //要解决的问题
        $qrcodeName = 'qrcode.png';
        $img->png($qrcodeText, $qrcodeName, $errorCorrectionLevel, $matrixPointSize);
        $backgroundInfo = getimagesize($postUrl);
        $backgroundFunc = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
        $background = $backgroundFunc($postUrl);
        $backgroundWidth = imagesx($backgroundInfo);
        $backgroundHeight = imagesy($backgroundInfo);
        $imageRes = imagecreatetruecolor($backgroundWidth, $backgroundHeight);
        $color = imagecolorallocate($imageRes, 0, 0, 0);
        imagefill($imageRes, 0, 0, $color);
        imagecopy($imageRes, $background, 0, 0, 0, 0, $backgroundWidth, $backgroundHeight);
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
    public function test(){
        $qrcodeContent = '此处存链接的话，参数不宜过长，否则导致生成二维码时间太长！！！';
        //容错级别
        $errorCorrectionLevel = 'L';
        //生成图片大小
        $matrixPointSize = 6;
        //生成二维码图片
        $img = new \QRcode();
        $img->png($qrcodeContent, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);


        $QR = public_path().'/'.'qrcode.png';//已经生成的原始二维码图
        //将二维码背景变透明
        $resource = imagecreatefrompng($QR);
        //@unlink($QR);
        $bgcolor = imagecolorallocate($resource, 255, 255, 255);
        imagefill($resource, 0, 0, $bgcolor);
        imagecolortransparent($resource, $bgcolor);
        header("Content-type:image/jpeg");

        imagepng($resource,'qrcode_copy.png');	/*先处理成透明图再进行缩放就不会出现白黑点情况了！！！（至少效果好多了，而先进行缩放再处理背景透明就会出现很多白黑点！）*/
        @imagedestroy($resource);
    }
}
