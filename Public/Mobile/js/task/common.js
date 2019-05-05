function errmsg(str,time) {
    layer.open({
        content: str
        ,skin: 'msg'
        ,time: time ? time :2 //2秒后自动关闭
    });
}
function succssmsg(str,time) {
    layer.open({
        content: str,
        btn: '确定',
        time:2
    });
}
function  isPoneAvailable(phone) {
    return true;
}