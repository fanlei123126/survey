function  redirectHandle(xhr) {

    var url = xhr.getResponseHeader("redirectUrl");
    //console.log("redirectUrl = " + url);

    var enable = xhr.getResponseHeader("enableRedirect");

    var status = xhr.getResponseHeader("login_status")


    if((enable == "true") && (url != "")){

        var win = window;
        while(win != win.top){
            win = win.top;
        }
        win.location.href = url+"?status="+status;
    }

}

$(function () {

    $(document).ajaxComplete(function (event, xhr, settings) {
        redirectHandle(xhr);
    })
})