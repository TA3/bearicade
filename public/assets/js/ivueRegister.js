Vue.component('register', {
    data: function() {
        return {
            name: '',
            username: '',
            email: '',
            password: '',
            cpassword: '',
            secret: '',
            tfaCode: '',
            step: 1,
            errors: [],

        }
    },
    mounted: function() {

    },
    template: '#registerTemplate',
    computed: {
        getStep: function() {
            return this.step;
        },
        submitEnabled: function() {
            if (this.step == 5) {
                return regex_tfa.test(this.tfaCode);

            }
        },
        allowLogin: function() {
            return (this.step == 1 && this.name == "");
        },
    },
    methods: {
        checkForm: function() {
            this.errors = [];
            switch (this.step) {
                case 1:
                    if (regex_name.test(this.name)) {
                        return true;
                    }
                    else {
                        this.errors.push(' Valid full name required (e.g. First Last).');
                        return false;
                    }
                    break;
                case 2:
                    if (regex_username.test(this.username) && this.username.length >= 4) {
                        return true;
                    }
                    else {
                        this.errors.push('Valid Username required.');
                        return false;
                    }
                    break;
                case 3:
                    if (regex_email.test(this.email)) {
                        return true;
                    }
                    else {
                        this.errors.push('Valid Email required.');
                        return false;
                    }
                    break;
                case 4:
                    if (this.password === this.cpassword) {
                        if (zxcvbn(this.password).score > 1) { //should be at least 1
                            return true;
                        }
                        else {
                            this.errors.push('Password is weak, stronger password required.');
                            return false;
                        }
                    }
                    else {
                        this.errors.push('Password does not match confirmation.');
                        return false;
                    }
                    break;
                case 5:
                    return true;
                default:
                    this.errors.push('Unknown Error, system adminstators contacted.');
                    return false;
            }
        },
        formRegister: function() {
            initLoader("#register");
            $.ajax({
                type: "POST",
                data: { name: this.name, username: this.username, email: this.email, password: this.password, cpassword: this.cpassword, tfaCode: this.tfaCode, secret: TFASecret },
                url: "/access/register/registeruser.php",
                success: function(data) {
                    //console.log(data);
                    var response = JSON.parse(data);
                    if (response.error) {
                        initLoader();
                        Vue.toasted.show(response.message, {
                            position: "top-right",
                            singleton: true,
                            duration: 60000,
                            action: {
                                text: 'Ok',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    }
                    else if (!response.error) {
                        Vue.toasted.show(response.message, {
                            position: "top-right",
                            singleton: true,
                            duration: 60000,
                            action: [{
                                    text: 'Dismiss',
                                    onClick: (e, toastObject) => {
                                        toastObject.goAway(0);
                                        initLoader();
                                    }
                                },
                                {
                                    text: 'Login',
                                    onClick: (e, toastObject) => {
                                        toastObject.goAway(0);
                                        window.location.href = "../";
                                    }
                                }
                            ],
                        });
                        initLoader("#register", "Account has been created, Please wait for an administrator to activate your account.");

                        //window.location.href = "../";
                    }
                },
                fail: function() {
                    initLoader();
                    Vue.toasted.show("Registeration failed.", {
                        position: "top-right",
                        singleton: true,
                        duration: 60000,
                        action: {
                            text: 'Ok',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);

                            }
                        },
                    });
                }
            });

        },
        nextStep: function() {

            if (this.getStep != 5 && this.checkForm()) {
                this.step++;
            }
        },
        previousStep: function() {
            if (this.getStep != 1 && this.checkForm()) {
                this.step--;
            }
        },
        showHelp: function() {
            Vue.toasted.show("Scan QR code with your 2FA app (Google Authenticator, Authy)", {
                position: "top-right",
                singleton: true,
                duration: 10000,
                action: {
                    text: 'Ok',
                    onClick: (e, toastObject) => {
                        toastObject.goAway(0);
                    }
                },
            });
            console.log(this.secret);

        }
    }
})

var Register = new Vue({
    data: {
        email: '',
        password: '',
        step: 1,
    },

    created: function() {

    },
    methods: {

    }
}).$mount('#register');
