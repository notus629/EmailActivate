<?php

    include 'db.php';
//var_dump($_POST);
    require './vendor/autoload.php';
    date_default_timezone_set('Asia/Shanghai');

    /*
     * User's infomation
     */
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    // 激活码
    $token = md5($username . $password . $email . uniqid());
    $status = 0;
    $token_expire = time() + 30*60; // 30 分钟有效
    $regtime = time();

    /*
     * Save user's info into db
     */
    $query = "INSERT INTO `mail_activate` (`id`, `username`, `password`, `email`, `status`, `token`, `token_expire`, `regtime`) VALUES (NULL, '$username', '$password', '$email', '$status', '$token', $token_expire, $regtime)";
    //var_dump($query);exit;
    $res = $db->query($query);


    if(!$res){
        var_dump($res);
        exit('数据库执行出错');
    }

    /*
     * Send an activation email to user
     */
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.exmail.qq.com';                   // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'notus@bugber.com';                // SMTP username
    $mail->Password = 'LNpuy3HFTzbBqsFk';                       // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    $mail->setFrom('notus@bugber.com', 'Notus');
    $mail->addAddress($email, $username);   // Add a recipient

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = '用户账号激活';
    $mail->Body    = "亲爱的 ". $username . "：<br />感谢您在我站注册了新账号。<br />请点击链接激活你的账号。<br /><a href='http://localhost:4000/activate.php?verify=" . $token . "' target='_blank'>http://localhost:4000/activate.php?verify=" . $token . "</a><br />如果以上链接无法点击，请将它复制到你的浏览器地址栏中进行访问，该链接 30 分钟内有效。<br >若此次激活请求非你本人所发，请忽略本邮件。<br /><p style='text-align: right;'>--------- bugber.com 敬上</p>";
    $mail->AltBody = '不支持html的邮件内容：This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
        $info = '邮件发送失败!<br />';
        $info .= 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        $info = '激活邮件已发送成功，请进入邮箱激活帐户';
    }
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>PHP用户注册-邮箱验证激活帐号</title>
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
    <h2 class="top_title">用户注册</h2>
    <div class="demo">
        <?php echo $info; ?>
    </div>
    <div id="footer">
        <p>Powered by Notus(hehe_xiao@qq.com)<a href="http://bugber.com">bugber.com</a></p>
    </div>
</div>

</body>
</html>