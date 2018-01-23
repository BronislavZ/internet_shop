<?php

$config  = array( 
        'mobile_phone' => '+38 063-063-63-63',
        'vk_url' => 'https://vk.com/royalstylevip', 
        'facebook_url' => 'facebook.php',
        'instagram_url' => 'https://www.instagram.com/royalstyle.vip/',
        'db'  =>   array('username' => 'root', 
                          'password' => '')
        );




$connection = new PDO("mysql:host=localhost; 
                       dbname=dianashop", 
                       $config['db']['username'],
                       $config['db']['password']
                       );
if($connection== false)
{
  echo 'Failed to connect to the database';
  echo mysqli_connect_error();
  exit();
}
?>