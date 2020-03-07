var regex_name = new RegExp(/^[A-Za-z,.-]{2,}\s[A-Za-z,.-]{2,}$/i);
var regex_username = new RegExp(/^[a-zA-Z0-9]+([_-]?[a-zA-Z0-9])*$/i);
var regex_email = new RegExp(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/);
var regex_tfa = new RegExp(/^[0-9]{6,6}$/);
