# imagecaptcha

加减乘除图片验证码

## Installation

```
$ composer require yonghua4413/imagecaptcha
```

```php
//加载
use Imagecaptcha\YYImagecode;

//生成
YYImagecode::make();

//后端验证
$code = $_SESSION['code'];

```

![Geetest Image Demo](http://wsqnxh.com/code.png)

## 常见问题

如遇图像无法输出，请在调用前加 ob_start();
