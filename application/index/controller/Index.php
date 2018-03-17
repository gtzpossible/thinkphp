<?php
namespace app\index\controller;

use think\Db;
use think\Controller;
use think\Session;
class Index extends Controller
{
	
	    public function index()
    {
		// return "<h1>TP5</h1>";
		// view/index/index.html
		
		return $this->fetch();
    }
	
	public function hello() {
		return "你好！";
	}
	
	/**
	 * 访问路径 public/index.php/index/index/db
	 */
	public function db() {
		// 1. 引入数据库类
		//    use think\Db;
		// 2. 获取Db对象
		// $db = Db::table("tedu_blog");
		// 已经配置了表前缀（database.php prefix）
		$db = Db::name("blog");
		
		// 查询多条记录
		// $blogs = $db->select();
		$blogs = $db->field(['id', 'title'])
		// ->where("id", "IN", "1,2,3")//字符串
		// ->where("id", "IN", [1,2,3]) // 数组
		->where("id", "BETWEEN", "1,4")
		// ->where("id", "BETWEEN", [1,3])
		// ->order("created DESC")
		->order(['created' => "DESC"])
		->select();
		// print_r($blogs);

		// 查询单条记录
//		$blog = $db->field("id, title, view")
//		           ->find(2);
				   
		$blog = $db->field("id, title, view")
		     // ->where("id=1") // 字符串
		     // ->where(['id' => 2]) // 数组
		     ->where('id', "eq", 1) // 三个参数
			 ->find();
		// print_r($blog);
		
		// 查询最热博客前五条
		$hotest = $db->field('id,title,view')
		  ->order("view DESC")
		  ->limit(5)
		  ->select();
		// print_r($hotest);
		
		// SELECT id,username FROM 表名
		$users = Db::name('user')
				->field('id,username')
				->order('created DESC')
				->page("3,10")
				->select();
				
	    print_r($users);
	}

	// 新增数据的方法
	// 访问路径：index.php/index/index/dbInsert
	public function dbInsert() {
		$db = Db::name("blog"); // 获取db对象
		$data = array(
			"title" => "联系insert插入数据",
			'content' => "insert后面的参数是一维的关联数组",
			'uid' => 1,
			'view' => 0,
			'updated' => time(),
			'created' => time()
		);
		// $res = $db->insert($data);
		
		// 返回自增主键id
		// $res = $db->insertGetId($data);
		// echo $res;
		// echo $res ? "插入成功" : "插入失败";
		
		$blogs = array(
			array(
				"title"   => "aaa",
				'content' => "aaa",
				'uid'     => 2,
				'created' => time()
			),
			array(
				"title"   => "bbb",
				'content' => "bbb",
				'uid'     => 2,
				'created' => time()
			),
			array(
				"title"   => "ccc",
				'content' => "ccc",
				'uid'     => 2,
				'created' => time()
			),
		);
		// $num = $db->insertAll($blogs);
		$num = $db->field("*")
				  ->where("id=1")
				  ->buildSql();
		
		echo $num;
	}

	// 验证更新数据操作
	// 访问路径：index.php/index/index/dbUpdate
	public function dbUpdate() {
		// 将用户表中余额为0的，统一改成50
		// (5分钟)
		// 1. 获取数据库对象
		$db = Db::name('user');
		
		// 2. 更新数据
//		$res = $db->where('balance', 'eq', 0)
//		          ->update(['balance'=>50]);
//				  
//		echo $res;
		
		// 将张三（id=1）的账户余额改为1000000
//		$res = $db->where("id","eq",1)
//		          ->setField("balance",1000000);
//				  
//		echo $res;

//		$res = Db::name("blog")
//			->where("id=1")
//			// ->setInc("view"); // 每次递增1
//			->setInc("view",5); // 每次递增1
//			
//		echo $res;

		// 把访问量是0的博客,全部删除（5分钟）
		$res = Db::name("blog")
		        ->where("view","eq",0)
				->delete();
		echo $res;
	}
	
	// 演示模板的调用和语法
	// 1. 继承 think\Controller
	// 2. 给模板赋值assign()
	// 3. 调用模板fetch()
	// 访问路径：index.php/index/index/tpl
	public function tpl()
	{
		// 获取id=1的博客详情
		$blog = Db::name("blog")->find(1);
		// print_r($blog);
		
		$json = json_encode($blog);
		// $arr = json_decode($json, true);
		$obj = json_decode($json); // 对象
		// print_r($obj);
		
		$blogs = Db::table("tedu_blog")
		        ->where("id","in", "1,2,3")
				->select();
		// print_r($blogs);
		
//		$this->assign("obj", $obj);
//		$this->assign("blog", $blog);
//		$this->assign("blogs", $blogs);
//		$this->assign("hi","hello,World");
		// assign一次赋多个值
		$this->assign([
			"obj"   => $obj,
			"blog"  => $blog,
			"blogs" => $blogs,
			"hi"    => "hello,World"
		]);
		
		// 模板：view/index/tpl.html
		return $this->fetch();
	}
}
