var fingerprint = '';
Vue.component('login', {
    data: function () {
        return {
            email: '',
            password: '',
            authCode: '',
            validEmail: false,
        }
    },
    mounted: function () {
        userBehaviour.config({
            userInfo: true,
            clicks: true,
            mouseMovement: true,
            mouseMovementInterval: 1,
            mouseScroll: true,
            timeCount: true,
            processTime: false,
        });
        userBehaviour.start();
        $('a.button').popup({
            on: 'click'
        });
        if (typeof Fingerprint2 !== 'undefined') {
            new Fingerprint2.get(function (result) {
                fingerprint = Fingerprint2.x64hash128(result.map(function (pair) {
                    return pair.value
                }).join(), 31);
            });
        }
    },
    template: '#loginTemplate',
    computed: {
        authCodeEnable: function () {
            if (this.email != '' && this.password != '' && this.validEmail) {
                return true
            } else {
                return false
            }
        },
        submitEnable: function () {
            //console.log(authCode.length);
            if (this.authCodeEnable && this.authCode != '' && this.authCode.length == 6) {
                return true
            } else {
                return false
            }
        },
        getFingerPrint: function () {
            return fingerprint
        }
    },
    watch: {
        email: function (input) {
            setTimeout(() => {
                this.validEmail = ValidateEmail(input);
            }, 500);
        }
    },
    methods: {
        formLogin: function () {
            if (this.submitEnable) {
                initLoader("#login");
                $.ajax({
                    type: "POST",
                    data: {
                        email: this.email,
                        password: this.password,
                        tfaCode: this.authCode,
                        fp: this.getFingerPrint
                    },
                    url: "/access/login.php",
                    success: function (data) {
                        //console.log(data);
                        var response = JSON.parse(data);
                        if (response.error) {
                            initLoader();
                            Vue.toasted.show(response.message, {
                                position: "top-right",
                                singleton: true,
                                duration: 50000,
                                action: {
                                    text: 'Cancel',
                                    onClick: (e, toastObject) => {
                                        toastObject.goAway(0);
                                    }
                                },
                            });

                        } else if (!response.error) {

                            var date = new Date();
                            date.setTime(date.getTime() + (2 * 60 * 60 * 1000));
                            var expires = " ; expires=" + date.toGMTString();
                            document.cookie = "authID=" + response.hash + ";  path=/" + expires + ";secure";
                            $.ajax({
                                type: "POST",
                                data: {
                                    user_behaviour: JSON.stringify(userBehaviour.showResult()),
                                    pathname: window.location.pathname
                                },
                                url: "/daemon/userBehaviour.php",
                                success: function (data) {
                                    var response = JSON.parse(data);
                                    if (response.error) {
                                        notify('Error in user behaviour. Contact administrators.', 'error');
                                    } else if (!response.error) {
                                        Vue.toasted.show(response.message, {
                                            position: "top-right",
                                            singleton: true,
                                            duration: 50000,
                                            action: {
                                                text: 'Cancel',
                                                onClick: (e, toastObject) => {
                                                    toastObject.goAway(0);
                                                }
                                            },
                                        });
                                        window.location.href = "../";
                                    }
                                },
                                fail: function () {
                                    notify('Request Timeout.', 'error');
                                }
                            });

                        }
                    },
                    fail: function () {
                        initLoader("#login");
                        notify('Request Timeout.', 'error');
                    }
                });
            }
        },


    }
})

var Login = new Vue({
    data: {
        email: '',
        password: '',
        authCode: ''
    },

    created: function () {

    },
    methods: {

    }
}).$mount('#login');