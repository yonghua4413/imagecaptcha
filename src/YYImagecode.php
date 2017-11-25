<?php
namespace imagecaptcha;
/**
 * 加减乘除法图片验证码
 * @author 254274509@qq.com
 */
class YYImagecode
{
	private static $_instance = null;
	private $width;
	private $height;
	private $session_name;
	private $image;

	private function __construct($width, $height, $session_name)
	{
		if(!isset($_SESSION))
        {
            session_start();
        }
        $this->width = $width;
        $this->height = $height;
        $this->session_name = $session_name;
        $this->image = imagecreatetruecolor($this->width, $this->height);
	}

	public static function make($width=100,$height=40, $session_name='code')
	{
		if(!self::$_instance instanceof self) 
		{ 
		    self::$_instance = new self($width, $height, $session_name);
		}
		$bgcolor = imagecolorallocate(self::$_instance->image, 255, 255, 255);//创建颜色
		imagefill(self::$_instance->image, 0, 0, $bgcolor);//将背景颜色填进图像区域填充
		self::$_instance->getCode();
		self::$_instance->interfere();
		self::$_instance->out();
	}
    
	/**
	 * 绘制计算式生成和生成结果至session
	 */
	private function getCode(){

		$frist = mt_rand(1, 100);
		$end   = mt_rand(1, 100);
		$fontsize = mt_rand(4,6);
		$fontcolor = imagecolorallocate($this->image, rand(1, 120), rand(1, 120), rand(1, 120));
		$x = (int)($this->width/8)+rand(3, 8);
		$y = rand(10, 20);
		if($frist < $end){
		    $method = '+';
		    if(($frist <= 10) && ($end <= 10)){
		        $method = '*';
		    }
		}else{
		    $method = '-';
		    if(($frist % $end) == 0){
		        $method = '/';
		    }
		}
		$fonttext = $frist. " $method " .$end. ' = ?';
		eval('$res = '. $frist . $method . $end.';');
	    imagestring($this->image, $fontsize, $x, $y, $fonttext, $fontcolor);
		$_SESSION[$this->session_name] = $res;
	}
	
    /**
     * 干扰和点
     */
	private function interfere(){
		/*干扰点*/
		for($i=0; $i<60; $i++){ 
			$pointcolor = imagecolorallocate($this->image,  rand(50, 120), rand(50, 120),  rand(50, 120));//干扰点的颜色
			imagesetpixel($this->image, rand(0, 100), rand(0, 30), $pointcolor);//画一个单一像素
		}
		/*干扰线*/
		for($i = 0; $i<3; $i++){ 
			$linecolor = imagecolorallocate($this->image,  rand(80, 220), rand(80, 220),rand(80,220));//干扰线的颜色
			imageline($this->image, rand(1, 99), rand(1, 29),rand(1, 99),rand(1, 29),$linecolor);//画一条线段
		}
	}
    
	/**
	 * 输出和销毁
	 */
	private function out(){
	    ob_clean();
		header('content-type:image/png');//输出内容格式，输出图像前一定要输出header
		imagepng($this->image);//将$image输出，输出图像
		imagedestroy($this->image);//销毁图像
	}
}
