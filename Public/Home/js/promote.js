/**
 * Created by 6 on 2016/6/2.
 */
$(function(){
    var bodyLeft = $('.promoteUrl');
    var bodyRight = $('.body_right');
    var changePanel = function(changeUrl){
        $.post(
            changeUrl,
            function(promoteStr){
                bodyRight.html(promoteStr);
            }
        )
    };

    bodyLeft.on('click', '>a', function (event) {
        bodyLeft.find('>a').removeClass('uc-current');
        var liDom = $(event.currentTarget);
        liDom.addClass('uc-current');
        var domId = liDom.attr('id');
        if(domId == 'promote1'){
            changePanel('../User/promote.html');
        }else if(domId == 'promote2'){
            changePanel('../User/sendMoney.html');
        }else if(domId == 'promote3'){
            changePanel('../User/myReward.html');
        }
        return false;
    });

    bodyRight.on('click', '.getPromoteMoneyCount', function(event){
        changePanel('../User/promoteCount.html');
    }).on('click', '.getPromotePersonCount', function(event){
        changePanel('../User/promotePersonCount.html')
    });

    changePanel('../User/promote.html');

    var clipboard = new Clipboard('#copy');
    clipboard.on('success', function(e) {
        console.log(e);
        alert("已复制");
        //$(".cpypt").show();
        //setTimeout(function(){$(".cpypt").hide();},1000);
    });

    clipboard.on('error', function(e) {
        console.log(e);
        alert("浏览器不支持直接复制，请选定连接后，ctrl+c复制");
    });
})