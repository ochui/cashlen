var csrfTokenJsFile = "";
$(document).ready(function () {
    csrfTokenJsFile = $('meta[name="csrf-token"]').attr("content");
});

function toggleMenu(){
    if($(document).width()<=767){
        $("body").removeClass("sidenav-toggled");
    }else {
        //$("body").addClass("sidenav-toggled");
    }
}
$(document).ready(function () {
    $(".demo-accordion").accordionjs();
    toggleMenu();
    if($('.copy-to-clipboard').length>0) {
        let clipboard = new ClipboardJS('.copy-to-clipboard');
        clipboard.on('success', function (e) {
            $.growl.notice({title: 'Success', message: "Content has been copied to clipboard."});
        });
    }
});
/*$(document).on("mouseleave",".app-sidebar",function () {
    $("body").addClass("sidenav-toggled");
});*/
$(document).on("click","#closeMenu",function (e) {
    e.preventDefault();
    $("body").removeClass("sidenav-toggled");
    return false;
});
$(window).resize(function(){
    toggleMenu();
});
$(document).on("mouseenter",".showInfoBox",function (e) {
    var obj = $(this);
    var leftPos = e.originalEvent.clientX;
    var topPos = e.originalEvent.clientY;
    var elem = obj.closest(".team-logo-box");
    var mainElement = elem.find(".team-user-info");
    var elemWidth = mainElement.width();
    if(mainElement.css("display")!='block') {
        leftPos = leftPos - 100;
        topPos = topPos - 150;
        mainElement.show().css({"left": leftPos + "px", "top": topPos + "px"});
    }
});
$(document).on("mouseleave",".showInfoBox",function () {
    var obj = $(this);
    var elem =obj.closest(".team-logo-box");
    elem.find(".team-user-info").hide();
});
$(window).on("load",function () {
   $(".img-load-bg").each(function () {
      var obj = $(this);
      var bgImg = obj.attr("data-src");
       obj.css('background','url("'+bgImg+'")');
   });
});

$(document).on("change",".change-row-status",function(){

    var current=$(this);
    var id=$(this).val();
    var status=$(this).prop("checked");
    var table=$(this).attr("data-table");
    var dataColumn=$(this).attr("data-column");

    $.post(baseUrl+"admin/change-row-status",{
        id:id,
        table:table,
        status:status,
        column:dataColumn,
        _csrf : csrfTokenJsFile
    },function(r){
        var obj=$.parseJSON(r);
        if(obj.status=="success")
        {
            if(obj.data!=undefined)
            {
                current.parent().html(obj.data);
            }
        }
    });

});
