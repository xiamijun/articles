<?php
/**
 * 封装数据库操作类
 * 单例模式
 */
class MySQLPDO{
    private $dbConfig=array(
        'db'=>'mysql',
        'host'=>'localhost',
        'port'=>'3306',
        'user'=>'root',
        'pass'=>'',
        'charset'=>'utf8',
        'dbname'=>''
    );
    private static $instance;   //单例模式
    private $db;    //保存pdo实例
    private $data=array();  //操作数据

    /**
     * MySQLPDO constructor私有构造方法.
     * @param array $params 数据库连接信息
     */
    private function __construct($params)
    {
        $this->dbConfig=array_merge($this->dbConfig,$params);
        $this->connect();
    }

    /**
     * 获得单例对象
     * @param array $params 数据库连接信息
     * @return MySQLPDO 单例对象
     */
    public static function getInstance($params=array()){
        if(!self::$instance instanceof self){
            self::$instance=new self($params);
        }
        return self::$instance; //返回对象
    }

    //私有克隆
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    //连接服务器
    private function connect(){
        $dsn="{$this->dbConfig['db']}:host={$this->dbConfig['host']};port={$this->dbConfig['port']}:dbname={$this->dbConfig['dbname']};charset={$this->dbConfig['charset']}";
        try{
            //实例化PDO
            $this->db=new PDO($dsn,$this->dbConfig['user'],$this->dbConfig['pass']);
        }catch (PDOException $e){
            die('数据库连接失败');
        }
    }

    //通过预处理方式执行sql，$batch表示是否批量处理
    public function query($sql,$batch){
        //取出成员属性中的数据并清空
        $data=$batch?$this->data:array($this->data);
        $this->data=array();
        //通过预处理方式执行sql
        $stmt=$this->db->prepare($sql);
        foreach ($data as $v) {
            if($stmt->execute($v)===false){
                die('数据库操作失败');
            }
        }
        return $stmt;
    }

    //保存操作数据
    public function data($data){
        $this->data=$data;
        return $this;
    }

    //取得一行结果
    public function fetchRow($sql){
        return $this->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    //取得所有结果
    public function fetchAll($sql){
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}