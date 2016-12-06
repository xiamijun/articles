<?php
require 'init.php';
$a=isset($_GET['a'])?$_GET['a']:'';

if($a=='category_add'){
    //安全过滤
    $data['name']=trim(htmlspecialchars($_POST['name']));

    if($data['name']===''){
        $error[]='文章分类名称不能为空';
    }else{
        $sql="select id from cms_category where name=:name";
        if($db->data($data)->fetchAll($sql)){
            $error[]='该文章分类已存在';
        }else{
            $sql="insert into cms_category(name) VALUES (:name)";
            $db->data($data)->query($sql);
        }
    }
}