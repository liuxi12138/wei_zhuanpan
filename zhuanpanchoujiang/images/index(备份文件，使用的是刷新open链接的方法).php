<?php
session_start();
header("Content-type: text/html;charset=utf-8");
error_reporting(0);
error_reporting(E_ERROR | E_PARSE);
$appid = "wx46fdb9fe3fd99085";  
$secret = "1c76215ebc53bd08194371dea2b2d85a";  
$code = $_GET["code"];  
$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';  
$con = mysqli_connect("qdm165426315.my3w.com","qdm165426315","cjh19940115")or die("数据库链接失败");
mysqli_set_charset ($con,utf8);
function https_request($url, $data = null) //url 请求函数
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

$output = https_request($get_token_url);
$json_obj = json_decode($output);
$array = get_object_vars($json_obj);
  
//根据openid和access_token查询用户信息  
$access_token = $array['access_token'];  
$openid = $array['openid'];  

$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';  
$output = https_request($get_user_info_url);
$user_obj = json_decode($output);
$user_array = get_object_vars($user_obj); 

$get_user_token ="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
$output2= https_request($get_user_token);
$output2 = json_decode($output2);
$array2 = get_object_vars($output2);//转换成数组
$access_token2= $array2['access_token'];

$get_user_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token2.'&openid='.$openid.'&lang=zh_CN';
$output3= https_request($get_user_url);
$output3 = json_decode($output3);
$array3 = get_object_vars($output3);//转换成数组
$subscribe= $array3['subscribe'];//输出subscribe 根据其值判断是否关注了公众号  

//解析json 

$_SESSION['user'] = $user_array;    

if($subscribe == 1){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
        <link rel="stylesheet" type="text/css" href="images/style.css">
        <title>抽奖啦！！！</title>
    </head>
    <body>

     <?php 
        $nickname = $_SESSION['user']['nickname'];
        $openid = $_SESSION['user']['openid'];
        mysqli_select_db($con,"qdm165426315_db");
        $sql="select * from wechar_draw where openid = '$openid'";
        $query = mysqli_query($con,$sql); 
        $row = mysqli_fetch_array($query); 
        if(empty($row['openid']))
        {
            $insert_sql="insert into wechar_draw (openid,nickname,prize_id,prize,isornot) values('$openid','$nickname',0,'',5)";
            mysqli_query($con,$insert_sql)or die("失败");
            $isornot=5;
        }else{
            $isornot = $row['isornot'];
        }
     ?>
    <?php
    if($isornot>0)
    {
    ?>
        <div class="main">
            <div class="top"><img src="images/bg1.jpg"></div>
            <div class="zhuanpan">
                <div class="zhuanpan_main">
                    <img class="kaishi" src="images/kaishi.png" id="startbtn">
                </div>
            </div>
            <div class="fenjiexian2">
                <img src="images/jiangpinshuoming.png">
            </div>
            <div class="green">
                <p>一等奖：隐形眼镜买一送一</p>
                <p>二等奖：50元代金券</p>
                <p>三等奖：眼睛清洁剂</p>
                <p>四等奖：眼镜盒</p>
                <p>五等奖：眼镜布</p>
            </div>
            <div class="fenjiexian">
                <img src="images/bg3.jpg">
            </div>
            <div class="orange">
                <img src="images/biaoti.jpg" class="orange_img">
            </div>
            <div class="fenjiexian">
                <img src="images/bg4.jpg">
            </div>
            <div class="qingse">
                <div class="left"><img src="images/tupian1.jpg"></div>
                <div class="right">
                    <p class="small_p">A.1.553折射率加硬加膜防辐射镜片</p>
                    <p class="big_p">原价114元 特价69元</p>
                    <p class="big_p">（赠送35元镜架）</p>
                    <img src="images/hr.png">
                    <p class="small_p">B.1.553折射率加硬加膜非球面防辐射镜片</p>
                    <p class="big_p">原价298元 特价99元</p>
                    <p class="big_p">（赠送45元镜架）</p>
                </div>
            </div>
            <div class="fenjiexian">
                <img src="images/bg5.jpg">
            </div>
            <div class="rouse">
                <div class="left">
                    <p class="small_p">C.60折射率超薄加硬加膜非球面防辐射镜片</p>
                    <p class="big_p">原价418元 特价129元</p>
                    <p class="big_p">（赠送69元镜架）</p>
                    <img src="images/hr.png">
                    <p class="small_p">D.67折射率高清超薄加硬加膜非球面防辐射镜片</p>
                    <p class="big_p">原价760元 特价169元</p>
                    <p class="big_p">（赠送99元镜架）</p>
                </div>
                <div class="right">
                    <img src="images/tupian2.jpg">
                </div>
            </div>
            <div class="fenjiexian">
                <img src="images/bg6.jpg">
            </div>
            <div class="orange">
                <div class="left"><img src="images/tupian3.jpg"></div>
                <div class="right">
                    <p class="big_p">·隐形眼镜系列：</p>
                    <p class="big_p">博士伦、卫康、海昌、海俪恩<span>批发价销售</span></p>
                    <p class="big_p">·买眼镜送镜架</p>
                    <p class="big_p">·现金代金券50相送</p>
                    <p class="small_p"><span>（本券只适用于购买品牌镜片，品牌镜片全场五折）</span></p>
                </div>
            </div>
            <div class="fenjiexian">
                <img src="images/bg7.jpg">
            </div>
            <div class="footer">
                <p>地址：理工大学西校区北门对过亿丰大厦二楼</p>
                <p>电话：15269309321&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;QQ：136212235</p>
            </div>
        </div>
    <?php }else{
        echo "<script>";
        echo "window.location.href='http://youthink.cc/zhuanpan/show.php?openid=".$openid."&nickname=".$nickname."';";
        echo "</script>";
        }?>

        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript" src="jQueryRotate.2.2.js"></script>
        <script type="text/javascript" src="jquery.easing.min.js"></script>
        <script type="text/javascript">
            $(function() {
                $("#startbtn").click(function() {
                    lottery();
                });
            });
            function lottery() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax.php',
                    data: {
                            openid: '<?php echo $openid;?>',
                            nickname: '<?php echo $nickname;?>'
                        },
                    dataType: 'json',
                    cache: false,
                    error: function() {
                        alert('Sorry，出错了！');
                        return false;
                    },
                    success: function(json) {
                        $("#startbtn").unbind('click').css("cursor", "default");//抽奖后即锁定转盘，不可再次抽奖
                        var angle = json.angle; //指针角度 
                        var prize = json.prize; //中奖奖项标题 
                        $("#startbtn").rotate({
                            duration: 3000, //转动时间 ms
                            angle: 0, //从0度开始
                            animateTo: 3600 + angle, //转动角度 
                            easing: $.easing.easeOutSine, //easing扩展动画效果
                            callback: function() {
                                switch(prize)
                                {
                                    case "谢谢惠顾":
                                        var jixu=confirm('没有中奖，再接再厉呦~\n您共有五次机会，是否继续抽奖？');
                                        break;
                                    default:
                                        var jixu=confirm('恭喜您中得' + prize + '\n您共有五次机会，是否继续抽奖？');
                                }
                                if (jixu) {
                                    window.location.href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx46fdb9fe3fd99085&redirect_uri=http://youthink.cc/zhuanpan/index.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";//跳转到静态页面上，静态页面仿照的是抽奖后的结果
                                }else{
                                    window.location.href="http://youthink.cc/zhuanpan/show.php?openid=<?php echo $openid;?>&nickname=<?php echo $nickname;?>";
                                }
                            }
                        });
                    }
                });
            }
        </script>

    </body>
</html>
<?php } else { 
     
$url = "http://mp.weixin.qq.com/s?__biz=MjM5NjM5NjkwMA==&mid=210685543&idx=1&sn=a347c9838168f4a5c022d7eed339b37b#wechat_redirect";  
echo "<script language='javascript' type='text/javascript'>";  
echo "window.location.href='$url'";  
echo "</script>";
}
?>
