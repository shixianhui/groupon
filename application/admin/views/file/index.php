<?php echo $tool; ?>
<table width='100%' border='0' cellspacing='0' cellpadding='0' >
<tr>
<td width="100px">当前目录：</td>
<td align="left">
<?php if (isset($path)){echo ".".$path;} ?>
</td>
<td align="center">
<?php if (isset($cusPath)) { ?>
<?php if ($cusPath != ":uploads:") { ?>
<a href="admincp.php/file/index/<?php echo $prePath; ?>">返回上级目录</a>
<?php }} ?>
</td>
</tr>
</table>
<table cellpadding="0" cellspacing="1" class="table_list">
<caption>文件信息</caption>
<tr>
 <th class="align_c" >文件名</th>
 <th width="10%">大小</th>
 <th class="align_c" width="10%">原图(宽x高)</th>
  <th class="align_c" width="10%">缩略图(宽x高)</th>
 <th width="15%">上次修改时间</th>
 <th width="10%">操作</th>
</tr>
<?php foreach ($files as $key=>$value ): ?>
<tr id="<?php echo preg_replace('/\//', ':', $path . $value["fileName"]); ?>" onMouseOver="this.style.backgroundColor='#f2f2f2'" onMouseOut="this.style.background='#FFFFFF'">
 <td align='left'>
 <?php if ($value["fileType"] == "dir"){?>
 <img src="images/admin/dir.gif" />
 <a href="admincp.php/file/index/<?php echo $cusPath . $value["fileName"] . ":" ; ?>"><?php echo $value["fileName"];?></a>
 <?php } else if ($value["fileType"] == "file") {?>
 <img src="<?php echo preg_replace('/\./', '_thumb.', substr($path, 1) . $value["fileName"]); ?>" width="80px" />
 <br/>
 <?php echo $value["fileName"];?>
 <?php }?>
 </td>
 <td width='55' class="align_c">
 <?php if ($value["fileType"] == "dir"){?>
 <目录>
 <?php } else if ($value["fileType"] == "file") {?>
 <?php echo $value["fileSize"];?>
 <?php }?>
 </td>
 <td class="align_c"><?php echo $value["artworkSize"];?></td>
 <td class="align_c"><?php echo $value["thumbnailSize"];?></td>
 <td class="align_c"><?php echo $value["fileMTime"];?></td>
 <td class="align_c"><?php if ($value["fileType"] == "file"){ ?><span class="deleteFile"><u>删除</u></span><?php } ?></td>
</tr>
<?php endforeach; ?>
</table>
<table width='100%' border='0' cellspacing='0' cellpadding='0' >
<tr>
<td width="100px">当前目录：</td>
<td align="left">
<?php if (isset($path)){echo ".".$path;} ?>
</td>
<td align="center">
<?php if (isset($cusPath)) { ?>
<?php if ($cusPath != ":uploads:") { ?>
<a href="admincp.php/file/index/<?php echo $prePath; ?>">返回上级目录</a>
<?php }} ?>
</td>
</tr>
</table>