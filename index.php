<?php
//只需要配置【1】【2】【3】【4】【5】【6】【7】
// 【1】设置预设的密码，随便写
$correctPassword = 'qqqqqq';
// 定时任务地址写法如下 http://网站/?pwd= qqqqqq
// 没有绑定网站就是，定时任务地址写法如下 http://ip:端口/?pwd= qqqqqq

// 【2】接收备份的目标邮箱
$targetEmail = 'abc@gmail.com';

// 【3】需要发送的目录列表, 会自动查找目录下的 .sql.gz 文件,若如要备份其他后缀文件，自行修改getFilesFromDirectories方法中的代码
$directories = [
    '/www/backup/database/mysql/crontab_backup/数据库目录1',
    '/www/backup/database/mysql/crontab_backup/数据库目录2'
];

// -------------------------------------------------------------------------------
// 获取当前时间
$currentDateTime = date('Y-m-d H:i:s');

// 检查 GET 参数中的密码
if (!isset($_GET['pwd']) || $_GET['pwd'] !== $correctPassword) {
    echo json_encode(['status' => 'error', 'message' => '无效的密码 - ' . $currentDateTime], JSON_UNESCAPED_UNICODE);
    exit;
}

// 引入PHPMailer的核心文件
require_once('PHPMailer/src/Exception.php');
require_once('PHPMailer/src/PHPMailer.php');
require_once('PHPMailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 获取目录下的文件
function getFilesFromDirectories($directories) {
    $files = [];
    foreach ($directories as $dir) {
        // 检查目录是否存在
        if (is_dir($dir)) {
            // 查找目录下的 .sql.gz 文件
            foreach (glob($dir . '/*.sql.gz') as $file) {
                $files[] = $file;
            }
        }
    }
    return $files;
}

// 使用 PHPMailer 发送邮件
$mail = new PHPMailer(true);
try {
    // 实例化PHPMailer核心类
    $mail = new PHPMailer();
    // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = 0;
    // 使用smtp鉴权方式发送邮件
    $mail->isSMTP();
    // smtp需要鉴权 这个必须是true
    $mail->SMTPAuth = true;

    // 如果不支持 tls ssl加密，下面都设置成false
    $mail->SMTPAutoTLS = false;
    $mail->SMTPSecure = false;

    // 设置ssl连接smtp服务器的远程服务器端口号
    $mail->Port = 25;
    // 设置发送的邮件的编码
    $mail->CharSet = 'UTF-8';

    // 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = '数据库备份文件';
    // 链接邮箱的服务器地址【4】
    $mail->Host = 'smtp.gmail.com';
    // smtp登录的账号 QQ邮箱或者其他邮箱即可【5】
    $mail->Username = 'abcd@gmail.com';
    // smtp登录的密码 使用生成的授权码【6】
    $mail->Password = '123456abcdefg';
    // 设置发件人邮箱地址 同【5】的登录账号
    $mail->From = 'abcd@gmail.com';【7】

    // 邮件正文是否为html编码 注意此处是一个方法
    $mail->isHTML(true);
    // 设置收件人邮箱地址
    $mail->addAddress($targetEmail);
    // 添加该邮件的主题
    $mail->Subject = '数据库备份时间：' . $currentDateTime;
    // 设置邮件内容
    $mail->Body = '数据库备份时间：' . $currentDateTime;

    // 获取并添加附件
    $files = getFilesFromDirectories($directories);
    foreach ($files as $file) {
        $mail->addAttachment($file);
    }

    // 发送邮件
    $mail->send();
    echo json_encode(['status' => 'success', 'message' => '邮件已发送 - ' . $currentDateTime], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => '邮件发送失败: ' . $mail->ErrorInfo . ' - ' . $currentDateTime], JSON_UNESCAPED_UNICODE);
}
?>
