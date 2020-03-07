var idiconoptions = {
    foreground: [0, 0, 0, 255], // rgba black
    background: [255, 255, 255, 255], // rgba white
    margin: 0.1, // 20% margin
    size: 420, // 420px square
    format: 'svg' // use SVG instead of PNG
};
var Menu;
var root = new Vue({
    data: {
        LoggedInUser: {
            userFullName: '',
            userRole: 'User',
            userEmail: '',
            userUserName: '',
            userIsAdmin: false,
            userLoaded: false,
            TFA: {
                QRImage: '',
                Secret: ''
            },
            API: {
                enabled: false,
                key: '',
                actions: '',
                domain: '',
                buttonEnabled: true
            }
        },
        SiteConfig: {
            config: {}
        }
    },
    beforeCreate: function () {
        $.ajax({
            type: "GET",
            url: "/controller/api.php?a=viewUser",
            cache: true,
            success: function (data) {
                if (data.error === false) {
                    Vue.set(root.LoggedInUser, 'userEmail', data.userDetails.email);
                    Vue.set(root.LoggedInUser, 'userFullName', data.userDetails.name);
                    Vue.set(root.LoggedInUser, 'userUserName', data.userDetails.username);
                    if (data.userDetails.isAdmin == "1") {
                        Vue.set(root.LoggedInUser, 'userIsAdmin', true);
                        Vue.set(root.LoggedInUser, 'userRole', 'Adminstrator');
                        //Temp solution for not displaying menu items for non admins
                    } else {
                        Vue.set(root.LoggedInUser, 'userIsAdmin', false);
                    }
                    var idiconoptions = {
                        foreground: [0, 0, 0, 255], // rgba black
                        background: [255, 255, 255, 255], // rgba white
                        margin: 0.1, // 20% margin
                        size: 420, // 420px square
                        format: 'svg' // use SVG instead of PNG
                    };

                    // create a base64 encoded SVG
                    var idicon = new Identicon(sha1(root.LoggedInUser.userUserName), idiconoptions).toString();
                    Vue.set(root.LoggedInUser, 'idiconbase64', "data:image/svg+xml;base64," + idicon);
                    Vue.set(root.LoggedInUser, 'userLoaded', true);
                }

            },
            fail: function () {

            }
        });
        var sessionExpire = $.get("/controller/api.php?a=sessionExpire", function () {

            }).done(function (data) {
                if (!data.error) {
                    Vue.set(root.LoggedInUser, 'sessionExpire', data.sessionExpire);
                    setInterval(() => {
                        try {
                            if (new Date(root.LoggedInUser.sessionExpire).getTime() < (new Date().getTime())) {
                                Vue.set(root.LoggedInUser, 'timeToExpire', "Session expired " + moment(root.LoggedInUser.sessionExpire, "YYYY-MM-DD hh:mm:ss").fromNow());
                            } else {
                                Vue.set(root.LoggedInUser, 'timeToExpire', "Expiring " + moment(root.LoggedInUser.sessionExpire, "YYYY-MM-DD hh:mm:ss").fromNow());
                            }
                        } catch (e) {
                            notify(e, 'error')
                        }
                    }, 1000)
                } else {
                    Vue.set(root.LoggedInUser, 'sessionExpire', null);
                }
            })
            .fail(function () {
                notify('Request Error', 'error');
            });
        var jqxhr = $.get("/controller/api.php?a=loadConfig", function () {

            })
            .done(function (data) {
                Vue.set(root.SiteConfig, 'config', data.config);
            })
            .fail(function () {
                notify('Request Error', 'error');
            });
        var Tfarequest = $.get("/controller/api.php?a=viewTFA", function () {

            })
            .done(function (data) {
                Vue.set(root.LoggedInUser.TFA, 'Secret', data.Secret);
            })
            .fail(function () {
                notify('Request Error', 'error');
            });
        var api_info = $.get("/controller/api.php?a=viewUserAPIInfo", function () {

            })
            .done(function (data) {
                if (data.userAPIInfo) {
                    var info = data.userAPIInfo;
                    var actions = JSON.parse(info.actions);
                    actions = actions.join(', ');
                    var domain = data.userAPIInfo.domain == null ? "No Domain Set" : data.userAPIInfo.domain;
                    Vue.set(root.LoggedInUser.API, 'actions', actions);
                    Vue.set(root.LoggedInUser.API, 'domain', domain);
                    Vue.set(root.LoggedInUser.API, 'enabled', true);
                } else {
                    Vue.set(root.LoggedInUser.API, 'actions', "No Access");
                    Vue.set(root.LoggedInUser.API, 'domain', "No Domains");
                }
            })
            .fail(function () {
                notify('Request Error', 'error');
            });
    },
    methods: {
        getInfo: function () {

        },

    },
    computed: {},
})