<?php
session_start();
$con = mysqli_connect("qdm165426315.my3w.com","qdm165426315","cjh19940115")or die("数据库链接失败");
mysqli_set_charset($con,"utf8");
mysqli_select_db($con,"qdm165426315_db");
$openid=$_GET['openid'];
$nickname=$_GET['nickname'];
$sql="select * from wechar_draw where openid='$openid'";
$query=mysqli_query($con,$sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
        <title>中奖啦！！！</title>
        <link rel="stylesheet" type="text/css" href="images/style.css">
    </head>
    <body>

        <div class="main">
            <div class="top"><img src="images/bg1.jpg"></div>
            <div class="zhuanpan">
                <font>恭喜你（<?php echo $nickname;?>）</font><br />
                <font>获得以下奖品：</font>
                <?php
                while($array=mysqli_fetch_array($query))
                {
                switch ($array['prize']) {
                    case '一等奖':
                        echo '<p>'.$array['prize'].'：隐形眼镜买一送一</p>';
                        break;
                    case '二等奖':
                        echo '<p>'.$array['prize'].'：50元代金券</p>';
                        break;
                    case '三等奖':
                        echo '<p>'.$array['prize'].'：眼镜清洁剂</p>';
                        break;
                    case '四等奖':
                        echo '<p>'.$array['prize'].'：眼镜盒</p>';
                        break;
                    case '五等奖':
                        echo '<p>'.$array['prize'].'：眼镜布</p>';
                        break;
                    case '<p>谢谢惠顾</p>':
                        break;
                    }
                }?>
                <p>(请获奖的同学到大光明眼镜店领奖)</p> <p>(地址如下，领奖请向店家出示本页面)</p>
            </div>
            <div class="footer">
                <p>地址：理工大学西校区北门对过亿丰大厦二楼</p>
                <p>电话：15269309321&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;QQ：136212235</p>
            </div>
            <style type="text/css">
            .zhuanpan p{
                font-size: 20px;
                line-height: 50px;
                color:#6e2924;
                font-weight: bold;
            }
            .zhuanpan{
                height:420px;
                padding-top:50px;
                text-align: center;
            }
            font{
                font-size: 26px;
                line-height: 70px;
                color:red;
                font-weight: bold;
            }
            </style>
        </div>
    </body>
</html>