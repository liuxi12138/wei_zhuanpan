<?php
session_start();
header("Content-type: text/html;charset=utf-8");
$con = mysqli_connect("数据库地址","用户名","密码")or die("数据库链接失败");
mysqli_set_charset($con,"utf8");
mysqli_select_db($con,"数据库名");
$openid=$_POST['openid'];
$nickname=$_POST['nickname'];

$sql="select * from wechar_draw where openid = '$openid'";
$query = mysqli_query($con,$sql); 
$row = mysqli_fetch_array($query); 
$isornot = $row['isornot'];
if($isornot>0)
{
    $prize_arr = array(
        '0' => array('id' => 1, 'min' => -29, 'max' => 29, 'prize' => '一等奖', 'v' => 100),
        '1' => array('id' => 2, 'min' => 31, 'max' => 89, 'prize' => '二等奖', 'v' => 100),
        '2' => array('id' => 3, 'min' => 91, 'max' => 149, 'prize' => '三等奖', 'v' => 100),
        '3' => array('id' => 4, 'min' => 151, 'max' => 209, 'prize' => '四等奖', 'v' => 100),
        '4' => array('id' => 5, 'min' => 211, 'max' => 269, 'prize' => '五等奖', 'v' => 100),
        '5' => array('id' => 6, 'min' => 271, 'max' => 329, 'prize' => '谢谢惠顾', 'v' => 500),//这里设置的是谢谢惠顾一直都是1000份，数据库里的数据可能会有所减少
     );
    for($i=1;$i<=6;$i++)
    {
        $prize_sql="select * from wechar_prize where id=$i";
        $prize_num[$i]=mysqli_fetch_array(mysqli_query($con,$prize_sql));
        if ($prize_num[$i]['count']<=0)
        {
            $prize_array[$i-1]['v']=0;
        }
    }
    foreach ($prize_arr as $v) {
        $arr[$v['id']] = $v['v'];
    }

    $prize_id = getRand($arr); //根据概率获取奖项id 

    $res = $prize_arr[$prize_id - 1]; //中奖项 

    $prize=$res['prize'];

    //$openid = $_SESSION['user']['openid'];
    //$nickname = $_SESSION['user']['nickname'];
    //$sql_prize = "select * from wx_prize where id='$prize_id'";
    $sql_openid = "select * from wechar_draw where openid='$openid'";
    $query_openid = mysqli_query($con, $sql_openid);
    $row_openid = mysqli_fetch_array($query_openid);

    //$query = mysqli_query($con, $sql_prize);
    //$prize_content = mysqli_fetch_array($query);
    $isornot=$row_openid['isornot'];
    if ($isornot==5){
        $isornot_1=$isornot-1;
        $update_sql="update wechar_draw set prize_id=$prize_id,prize='$prize',isornot=$isornot_1 where openid='$openid'";
        $update_count="update wechar_prize set count=count-1 where id=$prize_id";
        mysqli_query($con,$update_sql);
        mysqli_query($con,$update_count);
    }else{
        $isornot_1=$isornot-1;
        $update_sql="update wechar_draw set isornot=$isornot_1 where openid='$openid'";
        $insert_sql="insert into wechar_draw (openid,nickname,prize_id,prize,isornot) values('$openid','$nickname',$prize_id,'$prize',$isornot_1)";
        $update_count="update wechar_prize set count=count-1 where id=$prize_id";
        mysqli_query($con,$update_sql);
        mysqli_query($con,$insert_sql);
        mysqli_query($con,$update_count);
    }


    $min = $res['min'];
    $max = $res['max'];
    $data['angle'] = mt_rand($min, $max); //随机生成一个角度 
    $data['prize'] = $res['prize'];
    echo json_encode($data);
}else{
    $data['angle']=0;
    $data['prize']='不可以使用返回重复抽奖';
    echo json_encode($data);
}

    function getRand($proArr) {
        $data = '';
        $proSum = array_sum($proArr); //概率数组的总概率精度 
         
        foreach ($proArr as $k => $v) { //概率数组循环
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $v) {
                $data = $k;
                break;
            } else {
                $proSum -= $v;
            }
        }
        unset($proArr);

        return $data;
    }
