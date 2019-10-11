<?php

return [
    /*
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug'  => false,

    /*
     * 使用 Laravel 的缓存系统
     */
    'use_laravel_cache' => true,

    /*
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
    
    'test_account' => env('WECHAT_TEST_ACCOUNT', false),
    'test_app_id'  => env('WECHAT_TEST_APPID', 'wx32d387a2a086bbe2'),
    'test_secret'  => env('WECHAT_TEST_SECRET', '9f3f54426185ee51348dce9679bd5593'),

    'app_id'  => env('WECHAT_TEST_ACCOUNT', false) ? env('WECHAT_TEST_APPID', 'wx32d387a2a086bbe2') : env('WECHAT_APPID', 'wx293bc6f4ee88d87d'),
    'secret'  => env('WECHAT_TEST_ACCOUNT', false) ? env('WECHAT_TEST_SECRET', '9f3f54426185ee51348dce9679bd5593') : env('WECHAT_SECRET', '8f7e447b8eaea45b20b9362e8c404d93'),
    'token'   => env('WECHAT_TOKEN', 'bbtTech2013'),          // Token
    'aes_key' => env('WECHAT_AES_KEY', ''),                    // EncodingAESKey
    'iv_key'  => env('WECHAT_IV_KEY', ''),
    'crypt'   => env('WECHAT_CRYPT_METHOD', 'aes-256-cfb'),


    /*
     * 日志配置
     *
     * level: 日志级别，可选为：
     *                 debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => env('WECHAT_LOG_LEVEL', 'debug'),
        'file'  => env('WECHAT_LOG_FILE', storage_path('logs/wechat.log')),
    ],

    /*
     * OAuth 配置
     *
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
     */
     'oauth' => [
         'scopes'   => array_map('trim', explode(',', env('WECHAT_OAUTH_SCOPES', 'snsapi_userinfo'))),
         'callback' => env('WECHAT_OAUTH_CALLBACK', '/examples/oauth_callback.php'),
     ],

    /*
     * 微信支付
     */
    // 'payment' => [
    //     'merchant_id'        => env('WECHAT_PAYMENT_MERCHANT_ID', 'your-mch-id'),
    //     'key'                => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
    //     'cert_path'          => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/your/cert.pem'), // XXX: 绝对路径！！！！
    //     'key_path'           => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/your/key'),      // XXX: 绝对路径！！！！
    //     // 'device_info'     => env('WECHAT_PAYMENT_DEVICE_INFO', ''),
    //     // 'sub_app_id'      => env('WECHAT_PAYMENT_SUB_APP_ID', ''),
    //     // 'sub_merchant_id' => env('WECHAT_PAYMENT_SUB_MERCHANT_ID', ''),
    //     // ...
    // ],

    'media_get_url' => 'http://file.api.weixin.qq.com/cgi-bin/media/get?',
    'media_hq_get_url' => 'http://file.api.weixin.qq.com/cgi-bin/media/get/jssdk?',

    // 南校百步梯的key
    'sbbt_key' => env('SBBT_KEY'),
];
