<?php
namespace app\index\model;

use think\Model;

/**
 * 博客模型
 */
class User extends Model
{
	protected $autoWriteTimestamp=true;
	protected $updateTime="";
	protected $createTime="created";
	protected $insert=['balance'=>100,'created','password'];//添加时处理
	public function setCreatedAttr(){
		return time();
	}
	public function setPasswordAttr($value){
		return md5($value);
	}
}
