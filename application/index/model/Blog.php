<?php
namespace app\index\model;

use think\Model;

/**
 * 博客模型
 */
class Blog extends Model
{
	//开启时间自动写入     默认更新时间update_time   创建时间 create_time
	protected $autoWriteTimestamp=true;
	protected $updateTime="updated";
	protected $createTime="created";
	//开启自动完成
	protected $auto=[];//添加+更新
	protected $insert=['uid'];//添加时处理
	protected $update=[];//更新时处理
	protected function setUidAttr(){//设置uid自动完成的逻辑
		return !empty(Session('user'))?Session('user')['id']:0;
	} 
}
