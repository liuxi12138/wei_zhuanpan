<?php
session_start();
header("Content-type: text/html;charset=utf-8");
error_reporting(0);
error_reporting(E_ERROR | E_PARSE);
if(empty($_GET['openid'])||empty($_GET['nickname']))
{
    $appid = "微信后台有";  
    $secret = "微信后台也有";  
    $code = $_GET["code"];  
    $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';  
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
}else{
    $_SESSION['user']['openid']=$_GET['openid'];
    $_SESSION['user']['nickname']=$_GET['nickname'];
    $subscribe = 1;
}   

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
     ?>
     <script type="text/javascript">
        window.location.href="http://youthink.cc/zhuanpan/show.php?openid=<?php echo $openid;?>&nickname=<?php echo $nickname;?>";
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
