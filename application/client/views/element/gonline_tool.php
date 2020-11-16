<link href="css/default/online.css" rel="stylesheet" type="text/css">
<style>
#Ocs_floatingBox01-001 {
z-index:999;
position:fixed;
bottom:0;
right:0;

_position:absolute; /* for IE6 */
_top: expression(documentElement.scrollTop + documentElement.clientHeight-this.offsetHeight); /* for IE6 */
overflow:visible;
}  
</style>
<div>         
    <div name="在线客服" id="elem-Ocs_floatingBox01-001"> 
    <div class="Ocs_floatingBox01-d1_c1 float_horizon" id="Ocs_floatingBox01-001" style="top: 202.5px;"><!--折叠状态-->
	<div class="fold" id="ocsFold" onclick="getDisplay('ocsFold');" style="float: right;">
		<span class="title" style="line-height:20px;">&nbsp;</span>
		<span class="off" title="展开"></span>
	</div>
	<!--展开状态-->
	<div style="display:none;" class="unfold" id="ocsUnfold">
		<div class="box-top">
			<span class="title2">在线客服</span><a id="foldDivId" class="close" onclick="getDisplay('foldDivId');"  title="收起"></a>
		</div>
		<!--显示QQ/MSN等的模块-->
		<div class="talknet talknet-scroll">
		<ul>
			<li class="title2">
				买家咨询</li>
			<li class="talk_qq">
				ggggggggg</li>
			<li class="talk_qq">
				gggggggggggggg</li>
			<li class="talk_qq">
				ggggggggggg</li>
			<li class="title2">
				合作咨询</li>
			<li class="talk_qq">
				ggggggggggggg</li>
			<li class="title2">
				投诉与建议</li>
			<li class="talk_qq">
				ggggggggggggggg</li>
			<li class="talk_qq">
				gggggggggggggggggggg</li>
		</ul>
		</div>
		<div class="box-bottom">
			 <a id="foldDivId" class="close" onclick="getDisplay('foldDivId');"  title="收起"></a>
		</div>
	   </div>	
		<script type="text/javascript">
		function getDisplay(id) {
			if (id == 'foldDivId') {
				document.getElementById("ocsUnfold").style.display="none";
				document.getElementById("ocsFold").style.display="inline";
			} else {
				document.getElementById("ocsUnfold").style.display="inline";
				document.getElementById("ocsFold").style.display="none";
			}
		}
		</script>
        </div> 
        </div>
      </div>