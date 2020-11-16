<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="zh-CN"/>
<title>后台登录_设备管理系统</title>
<base href="<?php echo base_url(); ?>" />
<style type="text/css">
.main{background:url('images/admin/login-bg.png') no-repeat fixed;width:100%;height:100%;background-size:100% 100%;position:fixed;z-index:-1;top:0;left:0}
.login-box{margin:200px auto 0 auto;min-height:420px;max-width:420px;padding:40px;background-color:#ffffff;margin-left:auto;margin-right:auto;border-radius:4px;box-sizing:border-box}
.login-box a.logo{display:block;height:58px;width:167px;margin:0 auto 30px auto;background-size:167px 42px}
.login-box .message{margin:10px 0 0 -58px;padding:18px 10px 18px 60px;background:#189F92;position:relative;color:#fff;font-size:16px}
.login-box #darkbannerwrap{background:url('images/admin/aiwrap.png');width:18px;height:10px;margin:0 0 20px -58px;position:relative}
.login-box input[type=text],.login-box input[type=file],.login-box input[type=password],.login-box input[type=email],select{border:1px solid #DCDEE0;vertical-align:middle;border-radius:3px;height:50px;padding:0px 16px;margin-top: 10px;font-size:14px;color:#555555;outline:none;width:100%;box-sizing:border-box}
.login-box input[type=text]:focus,.login-box input[type=file]:focus,.login-box input[type=password]:focus,.login-box input[type=email]:focus,select:focus{border:1px solid #27A9E3}
.login-box input[type=submit],.login-box input[type=button]{display:inline-block;vertical-align:middle;padding:12px 24px;margin:0px;font-size:18px;line-height:24px;text-align:center;white-space:nowrap;vertical-align:middle;cursor:pointer;color:#ffffff;background-color:#189F92;border-radius:3px;border:none;-webkit-appearance:none;outline:none;width:100%}
.login-box hr{background:#fff url() 0 0 no-repeat}
.login-box hr.hr15{height:15px;border:none;margin:0px;padding:0px;width:100%}
.login-box hr.hr20{height:20px;border:none;margin:0px;padding:0px;width:100%}

.main .login-box .div-code .login-code{width:229px;-webkit-border-radius:2px 0 0 2px;-moz-border-radius:2px 0 0 2px;border-radius:5px 0 0 5px;margin:10px 0;float:left}
.main .login-box .div-code img{border:1px solid #DCDEE0;margin-right: :0px;width:99px;height:48px;float:right;display:block;margin-top:10px;background-color:#fff;-webkit-border-radius:0 2px 2px 0;-moz-border-radius:0 2px 2px 0;border-radius:0 5px 5px 0}
</style>
</head>
<body>
<div class="main">
    <div class="login-box">
        <div class="message"><?=$index_site_name?>-后台管理</div>
        <div id="darkbannerwrap"></div>
        <form name="myform" method="post" action="admincp.php/admin/login" class="layui-form">
            <!--用户名-->
            <input type="text" placeholder="请输入登录名" class="login-username" name="username" id="username" />
            <!--密码-->
            <input type="password" placeholder="请输入密码" class="login-password" name="password" id="password" />
            <!--验证码-->
            <div class="div-code">
            	<input type="text" placeholder="请输入验证码" class="login-code" name="code" id="code" style="text-transform:uppercase;"/>
            	<img width="70" src="admincp.php/verifycode/index/1" style="margin-bottom:-8px;cursor:pointer;" alt="看不清楚换一张?" onclick="javascript:this.src = this.src+1;">
            </div>
            <!--登陆按钮-->
            <input type="submit"  value="登录" id="dosubmit" class="login-submit" name="dosubmit"/>
            <hr class="hr20" >
        </form>
    </div>
</div>
</body>
</html>