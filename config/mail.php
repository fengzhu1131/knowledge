<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Driver
    |--------------------------------------------------------------------------
    |
    | Laravel supports both SMTP and PHP's "mail" function as drivers for the
    | sending of e-mail. You may specify which one you're using throughout
    | your application here. By default, Laravel is setup for SMTP mail.
    |
    | Supported: "smtp", "mail", "sendmail", "mailgun", "mandrill",
    |            "ses", "sparkpost", "log"
    |
    */
	//配置默认的邮件发送驱动，Laravel支持多种邮件驱动方式，包括smtp、Mailgun、Maildrill、Amazon SES、mail和sendmail
    'driver' => env('MAIL_DRIVER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | SMTP Host Address
    |--------------------------------------------------------------------------
    |
    | Here you may provide the host address of the SMTP server used by your
    | applications. A default option is provided that is compatible with
    | the Mailgun mail service which will provide reliable deliveries.
    |
    */
	//邮箱所在主机，比如我们使用163邮箱，对应值是smtp.163.com，使用QQ邮箱的话，对应值是smtp.qq.com
    'host' => env('MAIL_HOST', 'smtp.mdg-app.com'),

    /*
    |--------------------------------------------------------------------------
    | SMTP Host Port
    |--------------------------------------------------------------------------
    |
    | This is the SMTP port used by your application to deliver e-mails to
    | users of the application. Like the host we have set this value to
    | stay compatible with the Mailgun e-mail application by default.
    |
    */
	//配置邮箱发送服务端口号，比如一般默认值是25，但如果设置SMTP使用SSL加密，该值为465
    'port' => env('MAIL_PORT', 465),

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */
	//前者表示发送邮箱，后者表示发送邮件使用的用户名
    'from' => ['address' => env('MAIL_USERNAME'), 'name' => '记信服务器'],

    /*
    |--------------------------------------------------------------------------
    | E-Mail Encryption Protocol
    |--------------------------------------------------------------------------
    |
    | Here you may specify the encryption protocol that should be used when
    | the application send e-mail messages. A sensible default using the
    | transport layer security protocol should provide great security.
    |
    */
	//加密类型，可以设置为null表示不使用任何加密，也可以设置为tls/ssl
    'encryption' => env('MAIL_ENCRYPTION', 'ssl'),

    /*
    |--------------------------------------------------------------------------
    | SMTP Server Username
    |--------------------------------------------------------------------------
    |
    | If your SMTP server requires a username for authentication, you should
    | set it here. This will get used to authenticate with your server on
    | connection. You may also set the "password" value below this one.
    |
    */
	//邮箱账号，比如123@163.com
    'username' => env('MAIL_USERNAME'),

    /*
    |--------------------------------------------------------------------------
    | SMTP Server Password
    |--------------------------------------------------------------------------
    |
    | Here you may set the password required by your SMTP server to send out
    | messages from your application. This will be given to the server on
    | connection so that the application will be able to send messages.
    |
    */
	//上述邮箱登录对应登录密码
    'password' => env('MAIL_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Sendmail System Path
    |--------------------------------------------------------------------------
    |
    | When using the "sendmail" driver to send e-mails, we will need to know
    | the path to where Sendmail lives on this server. A default path has
    | been provided here, which will work well on most of your systems.
    |
    */
	//设置driver为sendmail时使用，用于指定sendmail命令路径
    'sendmail' => '/usr/sbin/sendmail -bs',
    //用于配置是否将邮件发送记录到日志中，默认为false则发送邮件不记录日志，如果为true的话只记录日志不发送邮件，这一配置在本地开发中调试时很有用
	'pretend' => true,
];
