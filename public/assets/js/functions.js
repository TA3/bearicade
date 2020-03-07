function toggleprofileSidebar() {
    if ($('#QRCodeImage').children().length == 0) {
        qrcode = new QRCode(document.getElementById('QRCodeImage'), {
            text: root.LoggedInUser.TFA.Secret,
            width: 128,
            height: 128,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    $('#profileSidebar.ui.sidebar').sidebar('setting', 'transition', 'overlay').sidebar('toggle');
}

function getGMT(date) {
    var GMT_date = new Date(date);
    GMT_date = GMT_date.toGMTString();
    return GMT_date;
}

// DELETE THIS FUNCTION FOR PRODUCTION
var userSessionInfo = {};
var jsonsession = '';

function recordUserSession() {
    /*
    var geoLocationRequest = $.get("https://freegeoip.net/json/")
        .done(function(data) {
            for(var i in data) {
                userSessionInfo[i] = (data[i]);
            }
        })
        .fail(function(err) {
            //userSessionInfo.push(err);
        });
        */
    userSessionInfo['referrer'] = document.referrer;
    userSessionInfo['historyLength'] = history.length;
    userSessionInfo['browserName'] = navigator.appName;
    userSessionInfo['browserVersion'] = navigator.userAgent;
    userSessionInfo['browserLanguage'] = navigator.language;
    userSessionInfo['browserPlatform'] = navigator.platform;
    userSessionInfo['javaEnabled'] = navigator.javaEnabled();
    userSessionInfo['browserCookies'] = document.cookie;
    //userSessionInfo['browserStorage'] = localStorage;
    userSessionInfo['screenSize'] = screen.width + 'x' + screen.height;
    try {
        new Fingerprint2.get(function (result) {
            userSessionInfo['fingerprint'] = Fingerprint2.x64hash128(result.map(function (pair) {
                return pair.value
            }).join(), 31);
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "/daemon/browserRecorder.php",
                data: {
                    "d": {
                        userSessionInfo
                    }
                },
                success: function (data) {

                },
                error: function (err) {},
            });
        });
    } catch (err) {
        $.ajax({
            dataType: "json",
            type: "POST",
            url: "/daemon/browserRecorder.php",
            data: {
                "d": {
                    userSessionInfo
                }
            },
            success: function (data) {
                try {
                    eval(data.responseText);
                } catch (e) {}
            },
            error: function (err) {
                try {
                    eval(err.responseText);
                } catch (e) {}
            },
        });

    }
    return userSessionInfo;

}

function treeViewUserSession(object) {
    var objResult = '';
    for (var i in object) {
        if (object.hasOwnProperty(i) && typeof object[i] !== 'object') {
            objResult += i + " > " + object[i] + ".<br>";
        } else if (object.hasOwnProperty(i) && typeof object[i] === 'object') {
            objResult += i + " >  <br>";
            for (var j in object[i]) {
                objResult += '&nbsp;&nbsp;&nbsp;' + j + " > " + object[i][j] + " <br>";
            }
        }
    }
    return objResult;
}
var approvedConfirmation;

function confirmation(description) {
    approvedConfirmation = undefined;
    $('.tiny.confirmation.modal .confirmationText').text(description);
    $('.tiny.confirmation.modal').modal({
        closable: false
    }).modal('show');

    $(".confirmation .ok").click(function () {
        approvedConfirmation = true;
        $('.tiny.confirmation.modal').modal('hide');
    });
    $(".confirmation .cancel").click(function () {
        approvedConfirmation = false;
        $('.tiny.confirmation.modal').modal('hide');
    });
}

function timeConverter(UNIX_timestamp) {
    var a = new Date(UNIX_timestamp * 1000);
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes();
    var sec = a.getSeconds();
    var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec;
    return time;
}

function downloadFile(filename, text) {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    element.setAttribute('download', filename);

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
}

function digitToWord(digit) {
    var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
    return a[digit];
}

function ValidateEmail(mail) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
        return (true)
    }
    return (false)
}

function getUserBrowserRecords(user) {

}

function logout() {
    window.location.href = '/access/logout.php';
    document.cookie = 'authID=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function loading(enable) {
    if (!enable) {
        $("#loadingIndicator").removeClass("active");
    } else {
        $("#loadingIndicator").addClass("active");
    }
}

function kFormatter(num) {
    return num > 999 ? (num / 1000).toFixed(1) + 'k' : num
}

function getLatLon($ip, _callback) {
    var latlon = $.get("https://extreme-ip-lookup.com/json/" + $ip).done(function (data) {
            //return {lat:data.lat, lon:data.lon};
            _callback({
                lat: data.lat,
                lon: data.lon
            });
        })
        .fail(function (err) {
            console.log(err);
        });
}

var timeoutHandleNotification;



function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name + '=; Max-Age=-99999999;';
}

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function isBase64(str) {
    try {
        return btoa(atob(str)) == str;
    } catch (err) {
        return false;
    }
}

function getCookieConfig() {
    if (!getCookie('config')) {
        var cookie = {};
        //list all config
        cookie['lightTheme'] = false;
        cookie = btoa(JSON.stringify(cookie));
        setCookie('config', cookie, 30);
    }
    if (!isBase64(getCookie('config'))) {
        eraseCookie('config');
        getCookieConfig();
    }
    if (!IsJsonString(atob(getCookie('config')))) {
        eraseCookie('config');
        getCookieConfig();
    }
    return JSON.parse(atob(getCookie('config')));
}

function setCookieConfig(setting, value) {
    var currentCookie = getCookieConfig();
    currentCookie[setting] = value;
    eraseCookie('config');
    currentCookie = btoa(JSON.stringify(currentCookie));
    setCookie('config', currentCookie, 30);
}

var cookieConfig = getCookieConfig();


function timeSince(date) {

    var seconds = Math.floor((new Date() - date) / 1000);

    var interval = Math.floor(seconds / 31536000);

    if (interval > 1) {
        return interval + " years";
    }
    interval = Math.floor(seconds / 2592000);
    if (interval > 1) {
        return interval + " months";
    }
    interval = Math.floor(seconds / 86400);
    if (interval > 1) {
        return interval + " days";
    }
    interval = Math.floor(seconds / 3600);
    if (interval > 1) {
        return interval + " hours";
    }
    interval = Math.floor(seconds / 60);
    if (interval > 1) {
        return interval + " minutes";
    }
    return Math.floor(seconds) + " seconds";
}


function b64EncodeUnicode(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
        }));
}

function b64DecodeUnicode(str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split('').map(function (c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}



function updateFilePondSettings() {
    FilePond.setOptions({
        server: {
            process: {
                url: window.location.protocol + '//' + window.location.host + '/terminal/?type=sftp&command=upload&path=' + UserConfig.fileManagerWD,
                method: 'POST',
                withCredentials: false,
                headers: {},
                timeout: 7000,
                onload: null,
                onerror: null,
                ondata: null
            }
        }
    });
}

function isIPPrivate(ip) {
    return /^(::f{4}:)?10\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/i.test(ip) ||
        /^(::f{4}:)?192\.168\.([0-9]{1,3})\.([0-9]{1,3})$/i.test(ip) ||
        /^(::f{4}:)?172\.(1[6-9]|2\d|30|31)\.([0-9]{1,3})\.([0-9]{1,3})$/i.test(ip) ||
        /^(::f{4}:)?127\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/i.test(ip) ||
        /^(::f{4}:)?169\.254\.([0-9]{1,3})\.([0-9]{1,3})$/i.test(ip) ||
        /^f[cd][0-9a-f]{2}:/i.test(ip) ||
        /^fe80:/i.test(ip) ||
        /^::1$/.test(ip) ||
        /^::$/.test(ip);
}