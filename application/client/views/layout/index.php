<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="zh-CN">
<meta http-equiv="x-ua-compatible" content="ie=7">
<base href="<?php echo base_url(); ?>" />
<meta name="title" content="<?php echo clearstring($title); ?>" />
<meta name="keywords" content="<?php echo clearstring($keywords); ?>" />
<meta name="description" content="<?php echo clearstring($description); ?>" />
<title><?php echo $title; ?></title>
<link href="css/default/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php echo $content; ?>
<?php echo $this->load->view('element/gonline_tool', NULL, TRUE); ?>
</body>
</html>