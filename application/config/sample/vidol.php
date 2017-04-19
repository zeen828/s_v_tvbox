<?php
defined('BASEPATH') or exit('No direct script access allowed');

//伺服器IP
$config['server_ip'] = '127.0.0.1';

// 寄送信件使用
$config['email_from'] = 'noreply@email'; // 寄件者
$config['email_reply'] = 'noreply@email'; // 信件回覆
$config['email_bcc'] = array(
		'noreply@email',
); // 不記名副本

// 會員忘記密碼用
$config['user_password_url'] = '';
$config['user_password_data'] = '';

// 會員認證用
$config['user_verify_url'] = ''; // 認證信位置
$config['user_verify_data'] = ''; // 認證信資料格式

// google authenticator key
$config['google_authenticator_key'] = '';