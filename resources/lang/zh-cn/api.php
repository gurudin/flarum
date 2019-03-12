<?php
return [
    /**
     * Login or register.
     */
    'validation' => [
        'nickname' => '昵称错误。',
        'email_max' => '邮箱长度超出，最长100字符。',
        'email_unique' => '邮箱已存在，换个邮箱试试！',
        'password' => '密码格式错误',
        'shareCode' => '邀请人码错误',
        'login_error' => '邮箱或者密码错误',
        'the_user_has_been_blocked' => '用户被禁止登入',
    ],
    'no_permission_to_read_the_post' => '没有权限阅读该帖子',
    'not_login' => '未登录',
    'login_date' => '登录过期',
];
