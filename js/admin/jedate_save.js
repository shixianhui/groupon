$("#inputdate_start").jeDate({
    onClose:false,
    isTime:false,
    format: "YYYY-MM-DD"
});
$("#inputdate_end").jeDate({
    onClose:false,
    isTime:false,
    format: "YYYY-MM-DD"
});
$("#end_time").jeDate({
    onClose:false,
    isTime:true,
    format: "YYYY-MM-DD hh:mm:ss"
});
$('#add_time').jeDate({
    onClose:false,
    festival: false,
    format: "YYYY-MM-DD hh:mm:ss",
    trigger: "click focus"
});