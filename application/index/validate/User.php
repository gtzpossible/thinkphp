<?php
//声明验证器
namespace app\index\validate;

use think\Validate;

class User extends Validate{//用户验证器
	//声明验证规则
	protected $rule=[
		'username'=>'require|length:2,100|unique:user',
		'email'=>'email',
		//'mobile'=>'mobile',
		'password'=>'require|min:6',
		'repassword'=>'require|confirm:password'
	];
	//声明验证提示
	protected $message=['username.require'=>'用户名必须填写',
						'username.length'=>'用户名长度非法(2~100位)',
				'username.unique'=>'用户名被使用请重新输入',
				'email.email'=>'邮箱格式非法',
			//	'mobile.mobile'=>'手机号码格式错误',
				'password.require'=>'密码不能为空',
				'password.min'=>'密码位数不能低于6位',
				'repassword.require'=>'确认密码不能为空',
				'repassword.confirm'=>'前后密码不一致'
				];
}

































?>