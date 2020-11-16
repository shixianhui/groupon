$(document).ready(function() {
	$("#upload_select").click(function(){
		$.post("/admincp.php/upload/select", 
				{	"file": "2010"
				},
				function(res){
					if(res.success){
						alert("操作成功!");
						return false;
					}else{
						alert("操作失败!");
						return false;
					}
				},
				"json"
		);
	});	
});