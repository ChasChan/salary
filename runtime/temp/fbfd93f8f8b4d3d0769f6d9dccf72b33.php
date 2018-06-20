<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:92:"E:\codes\thinkphp\thinkphp_5.0.20_with_ext\public/../application/index\view\index\index.html";i:1528870596;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<form action="<?php echo url('excel/getExcel'); ?>" enctype="multipart/form-data" method="post" accept-charset="UTF-8">
    <input type="file" name="wage" />
    <input type="submit" value="导入">
</form>
</body>
</html>