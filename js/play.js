_.core(function () {
    _.$("header")[0].outerHTML += "<a href='#' id='showHeader'>↓</a>";
    _.$("header nav.login ul").appendHTML("<li><a href='#' id='hideHeader'>x</a></li>");
    _.$("header").paddingRight("0px");
    App.init();
    _.$("body #hideHeader").click(function (e) {
        e.preventDefault();
        _.$("header").top("-322px").opacity("0");
        _.$("main").transition(".1s");
        _.$("main")[0].style.paddingTop = "0px"
    });

    _.$("body #showHeader").click(function (e) {
        e.preventDefault();
        _.$("header").top("0px").opacity("1");
        _.$("main")[0].style.removeProperty("padding-top");
    });
});