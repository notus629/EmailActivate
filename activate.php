<?php
/**
 * Created by PhpStorm.
 * User: Notus
 * Date: 17/7/28
 * Time: 上午8:53
 */

include 'db.php';

// user id activate
$info = activate();

function activate()
{
    $token = $_GET['verify'];

    $query = 'SELECT `id`, `username`, `token_expire`, `status` FROM `mail_activate` WHERE `token`="' . $token . '"';

    $res = $GLOBALS['db']->query($query);

    if (!$res){
        return '查询失败';
    }

    $data = $res->fetch(PDO::FETCH_ASSOC);

    if (!$data){
        return '激活码错误';
    }

    if (time() > $data['token_expire']){
        return '激活码已过期';
    }

    if ($data['status']){
        return '您的帐号已经激活，请勿重复操作';
    }

    $sql = 'UPDATE `mail_activate` SET `status` = :status WHERE `id` = :id';
    $pdoStatement = $GLOBALS['db']->prepare($sql);
    if (!$pdoStatement->execute([':id' => $data['id'], ':status' => 1])){
        return "激活失败";
    }

    return $data['username'] . " , 你的账号已激活成功!";
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>账号激活</title>
    <link rel="stylesheet" type="text/css" href="./css/style.css"/>
    <style type="text/css">
        .demo {
    width: 400px;
            margin: 40px auto 0 auto;
            min-height: 250px;
            color: #333;
        }

        .demo p {
    line-height: 30px;
            padding: 4px
        }

        .input {
    width: 180px;
            height: 30px;
            padding: 1px;
            line-height: 20px;
            border: 1px solid #999
        }

        .btn {
    position: relative;
    overflow: hidden;
    display: inline-block;
    *display: inline;
    padding: 4px 20px 4px;
            font-size: 14px;
            line-height: 18px;
            *line-height: 20px;
            color: #fff;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-color: #5bb75b;
            border: 1px solid #cccccc;
            border-color: #e6e6e6 #e6e6e6 #bfbfbf;
            border-bottom-color: #b3b3b3;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            margin-left: 54px
        }
    </style>
</head>
<body>
<div id="main">
    <h2 class="top_title">账号激活</h2>
    <div class="demo">
        <?php echo $info; ?>
</div>
<div id="footer">
    <p>Powered by Notus(hehe_xiao@qq.com)<a href="http://bugber.com">bugber.com</a></p>
</div>
</div>

</body>
</html>