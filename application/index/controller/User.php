<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Session;
use app\index\model\User as UserModel;
use app\index\model\Blog;
use think\Validate;
use think\Request;
use app\index\validate\User as UserValidate;
/**
 * 用户控制器
 */
class User extends Controller
{
	public function _initialize() {
		// 获取最热文章
		$hotest = Db::name('blog')
		        ->field('id, title, view')
				->order('view','DESC')
				->limit(5)
				->select();
		
		// 获取最新文章
		$newest = Db::name('blog')
		          ->field('id, title')
				  ->order('created', 'DESC')
				  ->limit(5)
				  ->select();
		
		$this->assign('hotest', $hotest);
		$this->assign('newest', $newest);
	}
	// 用户注册
	// 访问路径：index.php/index/user/register
	public function register() {
		
		// 调用模板
		// view/控制器名/方法名.html
		// view/user/register.html
		return $this->fetch();
	}
	
	// 注册执行路径
	public function doRegister() {
		//echo "hello";
//		print_r($_POST);
		
//		if ($_POST['password'] != $_POST['repassword']) {
//			echo "两次输入的密码不一致，请重新输入";
//			exit;
//		}
		//独立验证
//		$rule=['username'=>'require|unique|length:2,100','emali'=>'email'];
//		//验证消息
//		$msg=['username.require'=>'用户名必须填写',
//				//'username.unique'=>'用户名被使用请重新输入',
//				'username.length'=>'用户名长度非法(2~100位)',
//				'email'=>'邮箱格式非法'];
//		$v=new Validate($rule,$msg);
//		$data = array(
//			'username' => $_POST['username'],
//			'password' => $_POST['password'],
//			'email' => $_POST['email'],
//			'mobile' => $_POST['mobile'],
//			//'created' => time()
//		);
		if(!captcha_check($_POST['captcha'])){
			$this->error('验证码输入错误');
		}
		$data=$this->request->param();
//		if(!$v->check($data)){
//			$this->error($v->getError());
//		}
		$b=new UserModel;
		//$b->data($data);
		//allowFiel
		$res=$b->allowField(true)->validate(true)->save($data);
		if($res){
			$this->success("添加成功");
		}else{
			$this->error($b->getError());
		}
//		$db = Db::name('user');
//		$res = $db->insert($data);
//		echo $res ? "注册成功" : "注册失败";
	}
	public function login(){//展示登陆页面
		return $this->fetch();
	}
	public function doLogin(){//登陆执行页面
		//查找数据,需要构建模型  get返回的是对象
		//$res=UserModel::get(['username'=>$_POST['username'],'password'=>md5($_POST['password'])]);
		if(!captcha_check($_POST['captcha'])){
			$this->error('验证码输入错误');
		}
		$res=db('user')
				->where(['username'=>$_POST['username'],'password'=>md5($_POST['password'])])
				->find();
		if($res){
			Session::set('user',$res);
			
			$this->success("登陆成功","index/index");//第一参数提示消息,第二参数是跳转的页面
		}	else {
			$this->error("登录失败");//以前的方法 header("location:地址3")
		}
	}
	
	public function logout(){
		Session::delete("user");
		$this->redirect('index/index');
	}
	
	public function user(){
		if(Session::has('user.id')){//获取session的值
			$id=Session::get('user')['id'];
			//$id=Session::get('user.id');
			//session('user.id');
			$u=UserModel::get($id);
			$user=$u->toArray();//查找的登陆用户信息
			//Blog::all(['uid'=>$id]);查找全部信息,返回数组
			$blog=Blog::where("uid=$id")->paginate(4);//每页显示4条查询的消息
//		$blogs=$blog->toArray();''
			
			$this->assign('user', $user);
			$this->assign('blog', $blog);
		// 调用模板
		return $this->fetch();
		}else{
			$this->success("请先登录","user/login");
		}
	}
	
}
