<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<base href="<?php echo base_url(); ?>" />
<link type="text/css" rel="stylesheet" href="css/admin/system.css"/>
<link type="text/css" rel="stylesheet"  href="css/admin/imgareaselect-default.css" />
<title><?php echo $title; ?></title>
<script>
var controller = '<?php echo $this->uri->segment(1); ?>';
var method = '<?php echo $this->uri->segment(2); ?>';
var base_url = '<?php echo base_url(); ?>';
</script>
<?php if ($this->uri->segment(1) == 'admingroup' && $this->uri->segment(2) == 'save') { ?>
<script src="js/admin/jquery-1.4.2.min.js"></script>
<?php } else if ($this->uri->segment(1) == 'upload' && $this->uri->segment(2) == 'cutpicture') { ?>
<script src="js/admin/jquery-1.4.2.min.js"></script>
<script src="js/admin/jquery.imgareaselect.pack.js" type="text/javascript"></script>
<?php } else { ?>
<script src="js/admin/aui-artDialog/lib/jquery-1.10.2.js"></script>
<link rel="stylesheet" href="js/admin/aui-artDialog/css/ui-dialog.css">
<script src="js/admin/aui-artDialog/dist/dialog-plus-min.js"></script>
<?php } ?>
<script src="js/admin/jquery.form.js"></script>
<script src="js/admin/formvalid.js" type="text/javascript"></script>
<script src="js/admin/index.js?v=2.01" type="text/javascript"></script>
<script src="js/admin/calendar.js" type="text/javascript"></script>
    <link rel="stylesheet" href="css/admin/jedate.css">
<script src="js/admin/jquery.jedate.js" type="text/javascript"></script>
</head>
<body>
<?php echo $content; ?>
<br/>
<br/>
</body>
<script src="js/admin/jedate_save.js" type="text/javascript"></script>
</html>