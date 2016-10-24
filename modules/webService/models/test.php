<?php
    $ch = curl_init();
    $url = 'http://apis.baidu.com/xiaogg/holiday/holiday?d=20151001';
    $header = array(
        'apikey:d9e575c77e75e7d67f59942bdc749130',
    );
    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 执行HTTP请求
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);
    print_r($res);
?>
