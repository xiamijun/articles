<?php
require 'lib/MySQLPDO.php';
$dbConfig=array('user'=>'root','pass'=>'','dbname'=>'articles');
$db=MySQLPDO::getInstance($dbConfig);
//保存错误信息
$error=array();