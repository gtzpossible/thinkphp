<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Blog as BlogModel;
use think\Session;
use think\Request;
use app\index\model\User;
class Blog extends Controller
{
	/**
	 * 初始化函数
	 * 
	 * 完成控制器中公共的功能
	 */
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
	
	// 展示博客列表
	// 访问路径：index.php/index/blog/index
	public function index() {
		// $blogs = Db::name('blog')->paginate(4);
		// 通过助手函数调用数据库
		//$blogs = db('blog')->select();
		$blogs=BlogModel::where(2)->paginate(4);
		//$bs=BlogModel::all([1,2,3]);
//			print_r($bs);
		// 给模板赋值
		//$this->assign('list', $blogs);
		$this->assign('list', $blogs);
		// 调用模板
		return $this->fetch();
	}
	
	// 查看博客详情
	public function view($id)
	{
		
		$b = BlogModel::get($id);
		// print_r($b->title);
	//print_r($b->toArray());
		//print_r($b->toJson());
		// echo $id;
		// 查看博客详情，并在模板中展示输出（5分钟）
		//$blog = db('blog')->find($id);
		
		//$this->assign('data', $blog);
		$this->assign('data', $b->toArray());
		
		// view/blog/view.html
		return $this->fetch();
	}
	
	public function add(){
//		echo ROOT_PATH;
		//添加多条记录
//		$b=model('Blog');//不能使用别名
//		$data=array(
//		[
//		'title'=>'博客1','content'=>'呢日荣1','uid'=>3
//		],[
//		'title'=>'博客2','content'=>'呢日荣2','uid'=>3
//		]
//		);
//		$c=$b->saveAll($data);
//		print_r($c);
		return $this->fetch();
	}
	public function doAdd(){
		$data=$this->request->param();//获取post提交的数据
		$file=$this->request->file('img');//获取上传图片
		if(!empty($file)){
			//执行上传    路径:public/static/upload
			$info=$file->move(ROOT_PATH.'public'.DS.'static/upload');
			$data['img']=$info->getSaveName();//获取上传的路径(目录+随机名称 )
		}else{
			$data['img']="";
		}
//		$_POST['uid']=1;
		$b=new BlogModel;
		$res=$b->allowField(true)->save($data);
	//	$b->title=$_POST['title'];
		//$b->uid=$_POST['uid'];
	//	$b->content=$_POST['content'];
		//$b->created=time();//以上所有可以写在数组里
	//	$b->save();
		//$b=BlogModel::create($_POST);
		//获取post提交的数据
		//$this->request->post();
		/*$data=array(
		'title'=>$_POST['title'],'content'=>$_POST['content']
		);
		$res=Db::name('blog')->insert($data);*/
		if($res){
			$this->success("添加成功");
		}else{
			$this->error("添加失败");
		}
	}
//	public function edit($id){
//		$u=BlogModel::get($id);//更具id查找博客
//		$this->assign("u",$u->toArray());
//		return $this->fetch();
//	}
	public function edit(Request $request){
		//print_r($request->param());
		$id=$request->param()['id'];//请求参数
		if($request->isGet()){
			$u=BlogModel::get($id);//更具id查找博客
			$this->assign("u",$u->toArray());
			return $this->fetch();
		}elseif($request->isPost()){
			$data=$_POST;
			$res=BlogModel::update($data);//更新数组
		if($res){
			$this->success("更新成功");
		}else{
			$this->error("更新失败");
		}
		}
		
	}
	public function update(){
		$data=$_POST;
		$res=BlogModel::update($data);//更新数组
		if($res){
			$this->success("更新成功");
		}else{
			$this->error("更新失败");
		}
		//$res=BlogModel::where("id=1")->update(['view'=>200]);
		//先获取在更新
//		$b=BlogModel::get(1);
//		$b->view=300;
//		$b->save();
		//$b=new BlogModel;
		//$b->save(['view'=>500],['id'=>1]);
//		$data=array(
//		['id'=>1,'view'=>400],
//		['id'=>3,'view'=>400],
//		['id'=>4,'view'=>400]
//		);
//		$res=$b->saveAll($data);
//		print_r($res);
	}
	public function delete($id){
//		$b=BlogModel::get(26);
//		$b->delete();     "id" ,"gt","22"
		//BlogModel::where("id>22")->delete();
		//BlogModel::destroy(22);
		$u=BLogModel::destroy($id);
		if($u){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}
}
