<div class="clear"></div><div class="container1"><div class="link" id="com_indexistop_alinks" editok="online"><h1> 友情链接 </h1><ul><?php    $linkList = $this->advdbclass->getLink();?><?php if($linkList): ?><?php foreach ($linkList as $link): ?><li class="i_l_link01"><a href="<?php echo $link['url']; ?>" title="<?php echo $link['site_name']; ?>" target="_blank"><?php echo $link['site_name']; ?></a></li><?php endforeach; ?><?php endif; ?></ul></div></div><div class="footer mtop"><h1> </h1><ul><div id="com_othermenu" editok="online"><a href="<?php if ($html){echo 'index.html';} else {echo $client_index;} ?>"><?php echo $index_name; ?></a><?php    $footerMenuList = $this->advdbclass->getFooterMenu();    if ($footerMenuList) {	foreach ($footerMenuList as $footerMenu) {		if ($footerMenu['menu_type'] == '3') {    		$url = $footerMenu['url'];    		    	} else {    		$url = getBaseUrl($html, $footerMenu['html_path'], "{$footerMenu['template']}/index/{$footerMenu['id']}.html", $client_index);        }	?><a> | </a><a href="<?php echo $url; ?>"><?php echo $footerMenu['menu_name'] ?></a><?php }} ?></div><span id="com_copyright" editok="online"><?php echo $site_copyright; ?><?php echo $icp_code; ?></span></ul></div></div><div class="left"><img src="/images/default/right.jpg" /></div></div>