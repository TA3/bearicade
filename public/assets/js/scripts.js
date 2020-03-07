$(document).ready(function() {
    $('#languagesDropdown').dropdown();
    $('.ui.accordion').accordion();
    $('.ui.dropdown').dropdown();
    $('.ui.checkbox').checkbox();
    $('.menu .item').tab();

    recordUserSession();
     $.get("../../daemon/online.php");
    var stillAlive = setInterval(function() {
        /* XHR back to server
           Example uses jQuery */
        $.get("../../daemon/online.php");
    }, 60000);
});
