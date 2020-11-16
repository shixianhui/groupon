$(document).ready(function() {
	/****添加/编辑****/	
	$('#jsonForm').ajaxForm({
        dataType:  'json',
        beforeSubmit: showRequest,
        success:   showReponse 
    });
	function showReponse(data) {
		if(data && data.success){
			if (data.field == 'success_center') {
				var d = dialog({
					width:300,
				    title: '提示',
				    fixed: true,
				    content: data.message				    
				});
				d.show();
				setTimeout(function () {
				    d.close().remove();
				    $("#contact_name").val("");
				    $("#mobile").val("");
				    $("#email").val("");
				    $("#g_content").val("");
//				    $("#history>option").each(function(i,n){
//			        	if($(n).val() == ""){
//			        		$("#history").get(0).options[i].selected = true;
//			        		return;
//			        	}
//			        });
				}, 2000);
				return false;
			} else if (data.field == 'success_meet') {
				var d = dialog({
					width:300,
				    title: '提示',
				    fixed: true,
				    content: data.message				    
				});
				d.show();
				setTimeout(function () {
				    d.close().remove();
				    $("#meet_contact_name").val("");
				    $("#meet_mobile").val("");
				    $("#meet_email").val("");
				    $("#meet_time").val("");
				    $("#meet_content").val("");
				    $(".xinzeng").hide();
				}, 2000);				
				return false;
			} else {
				window.location.href = data.message;
			}
			return false;
		}else{
			if (data.field != 'fail') {
				var d = dialog({
				    title: '提示',
				    width:300,
				    fixed: true,
				    content: data.message
				});
				d.show();
				setTimeout(function () {
				    d.close().remove();
				    $("#"+data.field).focus();
				}, 2000);
				return false;
			} else {
				var d = dialog({
				    title: '提示',
				    width:300,
				    fixed: true,
				    content: data.message
				});
				d.show();
				setTimeout(function () {
				    d.close().remove();
				}, 2000);
				return false;
			}			
			return false;
		}
	}
	function showRequest() {
		if(validator(document.forms['jsonForm'])) {
			return true;
		} else {
			return false;
		}
	}
});