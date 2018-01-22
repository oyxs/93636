<?php

echo 11111111;
$access_token=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx1a975a230a9ed1ea&secret=dd0831a09d21d60a45c6bfa13a9f65bb");
$arr = json_decode($access_token,true);
print_r($access_token);


?>