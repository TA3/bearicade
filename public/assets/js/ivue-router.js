var UsersData = {
    users: [],
    selectedUser: {},
    isInUserViewMode: false,
    isInUserViewInfoMode: true,
    isInUserMonitorMode: false,
    isInUserViewActivityMode: false,
    isInUserView2FAMode: false,
    isInUserViewAPIPermissions: false,
    syncingUsers: {
        synced: false,
        syncing: false
    },
    searchUsersInput: '',
    showGroups: false,
    groupsList: {},
    createGroupName: '',
    groupCreationEnabled: false,
    logs: {
        requests: null,
        numberOfRequests: 10
    },
    job: {
        name: '',
        nodes: 1,
        PPN: 1,
        fileName: '',
        jobCode: '',
        limits: {
            nodes: {
                min: 1,
                max: 7
            },
            PPN: {
                min: 1,
                max: 4
            },
            memory: {
                max: 0
            }
        }
    },
};
var UserConfig = {
    terminalFileManagerControlsEnabled: true,
    terminalFileManagerEnabled: false,
    terminalEnabled: false,
    terminalExpanded: false,
    fileManagerEnabled: false,
    fileManagerUploadEnabled: false,
    fileManagerJobTemplateEnabled: false,
    fileManagerList: null,
    fileManagerWD: '',
    fileManagerEditorEnabled: false,
    fileManagerEditorPath: '',
    fileManagerEditorFileOwner: '',
    fileManagerEditorDelete: '',
    serverSelectionEnabled: false,
    selectedServer: null,
    terminalHistory: [],
    terminalHistoryAt: 0,
    blockedAttemptsEnabled: false
};
var sessionsGeo;
var fileManagerEditorFlask;
// 1. Define route components.
var Home = {
    template: '#home',
    data: function () {
        return {
            UsersData,
            UserConfig,
            TGMessage: '',
            CommandInput: ''
        }
    },
    components: {
        FilePond: vueFilePond.default(FilePondPluginImagePreview)
    },
    mounted: function () {

    },
    methods: {
        toast: function (m, t) {
            Vue.toasted.show(m, {
                position: "top-right",
                singleton: true,
                duration: t,
                action: {
                    text: 'Cancel',
                    onClick: (e, toastObject) => {
                        toastObject.goAway(0);
                    }
                },
            });
        },
        viewGeoMap: function () {
            $("#geo_map_container").animate({
                height: "500px"
            }, 750, function () {
                var map = new GMaps({
                    div: '#geo_map',
                    lat: -12.043333,
                    lng: -77.028333,
                    zoom: 1,
                    styles: map_styles
                });
                $.ajax({
                    url: '../../controller/api.php?a=sessionsGeo',
                    type: 'get',
                    success: function (data) {
                        data.sessionsGeo.forEach(element => {
                            getLatLon(element.ip, function ($latlon) {
                                map.addMarker({
                                    lat: $latlon.lat,
                                    lng: $latlon.lon,
                                    title: element.uid,
                                    icon: 'assets/images/pointer.png',
                                    infoWindow: {
                                        content: '<div class="ui black label"><i class="user icon"></i> <i class="hashtag icon"></i> ' + element.uid + '</div>'
                                    }
                                });
                            });

                        });
                    },
                    error: function () {

                    }
                });
            });

        },
        listUsers: function () {
            Users.listUsers
        },
        initTerminalFileManager: function () {
            UserConfig.terminalEnabled = false;
            $('#terminalDimmer').fadeIn(500, function () {
                $('#terminal .connect').hide();
                UserConfig.terminalFileManagerEnabled = true;
                UserConfig.terminalEnabled = true;
                $('#terminalDimmer').fadeOut();

            });
            this.initFileManager();

        },
        initFileManager: function () {
            setTimeout(function () {
                $('#serverSelectionDropdown').dropdown({
                    onChange: function (value) {
                        UserConfig.selectedServer = value;
                    }
                });
                if (UserConfig.selectedServer == null) {
                    UserConfig.selectedServer = UserConfig.servers[0].name;
                    $('#serverSelectionDropdown').dropdown('set selected', UserConfig.servers[0].name);
                }
            }, 2000);
            setTimeout(function () {
                $('#terminal i').popup();
            }, 1000);
            $('#terminalDimmer').fadeIn(500, function () {
                UserConfig.terminalEnabled = false;
                UserConfig.fileManagerEnabled = true;
                UserConfig.fileManagerUploadEnabled = false;
                UserConfig.fileManagerJobTemplateEnabled = false;
                $('#terminalDimmer').fadeOut();
            });
            this.fileManagerListPath();



        },
        initFileManagerUploader: function () {
            updateFilePondSettings();
        },
        fileManagerJobTemplateGenerateCode: function () {
            UsersData.job.jobCode = `#!/bin/bash\n#PBS -l nodes=` + UsersData.job.nodes + `:ppn=` + UsersData.job.PPN + `\n#PBS -N ` + UsersData.job.name + `\n` + "nprocs=`wc-l $PBS_NODEFILE | awk'{ print $1 }'` " + `\nmpirun -np ` + UsersData.job.nodes * UsersData.job.PPN + ` -machinefile $PBS_NODEFILE ` + UserConfig.fileManagerWD + `/` + UsersData.job.fileName;
        },
        fileManagerJobTemplateSave: function (submit) {

            if (parseInt(UsersData.job.nodes) < UsersData.job.limits.nodes.min ||
                parseInt(UsersData.job.nodes) > UsersData.job.limits.nodes.max ||
                parseInt(UsersData.job.PPN) < UsersData.job.limits.PPN.min ||
                parseInt(UsersData.job.PPN) > UsersData.job.limits.PPN.max ||
                UsersData.job.name == "" ||
                UsersData.job.fileName == "") {
                Vue.toasted.show("Error: Check inputted data", {
                    position: "top-right",
                    singleton: true,
                    duration: 3000,
                    action: {
                        text: 'Cancel',
                        onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                        }
                    },
                });
                return 0;
            }
            $('#terminalDimmer').fadeIn(100);
            var jqxhr = $.get("../../terminal/?type=sftp&command=put&new&path=" + UserConfig.fileManagerWD + "/" + UsersData.job.name + ".job&data=" + Base64.encode(UsersData.job.jobCode.replace(/\\n/g, "\\n")), function () {

                })
                .done(function (data) {
                    if (!data.success) {
                        $('#terminalDimmer').fadeOut(100);
                        Vue.toasted.show("Error: saving job file failed", {
                            position: "top-right",
                            singleton: true,
                            duration: 3000,
                            action: {
                                text: 'Cancel',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    } else {
                        UserConfig.fileManagerEditorEnabled = false;
                        UserConfig.terminalFileManagerControlsEnabled = true;
                        UserConfig.fileManagerEditorPath = "";
                        $('#fileManagerEditor').fadeOut();
                        $('#terminalDimmer').fadeOut(100);
                        if (submit) {
                            var jqxhr = $.get("../../terminal/?type=ssh&command=qsub " + UsersData.job.fileName + "&path=" + UserConfig.fileManagerWD + "&server=cygnus", function () {

                                })
                                .done(function (data) {
                                    Vue.toasted.show("Job File Submitted", {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 3000,
                                        action: {
                                            text: 'Cancel',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                })
                                .fail(function (err) {
                                    Vue.toasted.show("Error: failed submitting job", {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 3000,
                                        action: {
                                            text: 'Cancel',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                });
                        }
                        Vue.toasted.show("Job File Saved", {
                            position: "top-right",
                            singleton: true,
                            duration: 3000,
                            action: {
                                text: 'Cancel',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    }
                })
                .fail(function (err) {
                    $('#terminalDimmer').fadeOut(100);
                    Vue.toasted.show("Request Error", {
                        position: "top-right",
                        singleton: true,
                        duration: 3000,
                        action: {
                            text: 'Cancel',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });

        },
        fileManagerListPath: function (path, filename) {

            if (filename == "..") {
                console.log(path);
                path = path.replace(/[\/]([a-zA-Z0-9\s_\\.\-\(\):])*[\/]?$/, "");

                filename = "";
                console.log(path + "/" + filename);

            }
            if (path == null || path == "" || path == undefined) {
                path = "home/" + root.LoggedInUser.userUserName
            }
            if (filename == null || filename == "" || filename == undefined) {
                filename = "";
            }
            var jqxhr = $.get("../../terminal/?type=sftp&command=ls&path=" + path + "/" + filename, function () {
                    loading();

                })
                .done(function (data) {
                    loading(false);
                    if (data.error) {
                        Vue.toasted.show("Error: " + data.error, {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    } else {
                        UserConfig.fileManagerList = data.output;
                        UserConfig.fileManagerWD = data.wd.replace(/[\/]{2,}/, "").replace(/[\/]$/, "");
                        $('#terminal i').popup();
                    }
                })
                .fail(function (err) {
                    loading(false);
                    Vue.toasted.show("Error: " + err, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });
        },
        initTerminal: function () {

            $('#terminalDimmer').fadeIn(500, function () {
                UserConfig.fileManagerEnabled = false;
                UserConfig.terminalEnabled = true;
                $('#terminalDimmer').fadeOut();

            });
            setTimeout(function () {
                $('#terminal a').popup();
                $('#terminal i').popup();
            }, 1000)

        },

        closeTerminal: function () {
            $('#terminalContainer').slideUp('slow', function () {
                $('#terminalContainer').remove();
            });
        },
        sendTGMessage: function () {
            var jqxhr = $.get("../../controller/api.php?a=sendTGMessage&message=" + this.TGMessage, function () {
                    loading();
                })
                .done(function (data) {
                    loading(false);
                    if (!data.error) {
                        Vue.toasted.show("Message Sent ", {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    } else {
                        Vue.toasted.show("Error: " + data.error, {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    }
                })
                .fail(function (err) {
                    loading(false);
                    Vue.toasted.show("Error: " + err, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });
        },
        previousHistory: function () {
            this.CommandInput = UserConfig.terminalHistory[UserConfig.terminalHistoryAt];
            if (UserConfig.terminalHistoryAt != 0) {
                UserConfig.terminalHistoryAt--;
            }
        },
        nextHistory: function () {
            if (UserConfig.terminalHistoryAt != UserConfig.terminalHistory.length - 1) {
                UserConfig.terminalHistoryAt++;
            }
            this.CommandInput = UserConfig.terminalHistory[UserConfig.terminalHistoryAt];
        },
        CommandSubmit: function (e) {
            if (e) e.preventDefault();
            if (this.CommandInput == "") return;
            if (this.CommandInput == "exit") {
                $('#terminal form').hide();
                $('#terminal .connect').fadeIn();
                UserConfig.terminalEnabled = false;
            }
            UserConfig.terminalHistory.push(this.CommandInput);
            UserConfig.terminalHistoryAt = UserConfig.terminalHistory.length - 1;
            document.getElementById("CommandOutput").innerText += this.LoggedInUser.userUserName + "@" + this.UserConfig.selectedServer + ":" + this.fileManagerWD + "$ " + this.CommandInput + "\n";

            var jqxhr = $.get("../../terminal/?type=ssh&command=" + this.CommandInput + "&path=" + this.fileManagerWD + "&server=" + this.UserConfig.selectedServer, function () {
                    loading();
                })
                .done(function (data) {
                    loading(false);
                    if (data.error) {

                    } else {
                        var objDiv = document.querySelector("#CommandOutput");
                        objDiv.innerText += data.output;
                        objDiv.scrollTop = objDiv.scrollHeight;
                    }
                })
                .fail(function (err) {
                    loading(false);

                });
            this.CommandInput = "";
        },
        timeConverter: function (t) {
            var a = new Date(t * 1000);
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var year = a.getYear();
            var month = months[a.getMonth()];
            var date = a.getDate();
            var hour = a.getHours();
            var min = a.getMinutes();
            var sec = a.getSeconds();
            var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec;
            return time;
        },
        formatBytes: function (a, b) {
            if (0 == a) return "0 Bytes";
            var c = 1024,
                d = b || 2,
                e = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
                f = Math.floor(Math.log(a) / Math.log(c));
            return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f]
        },
        urldecode: function (url) {
            return decodeURIComponent(url.replace(/\+/g, ' '));
        },
        fileManagerEditFile: function (file) {
            $('#terminalDimmer').fadeIn(100);
            var jqxhr = $.get("../../terminal/?type=sftp&command=get&path=" + file, function () {

                })
                .done(function (data) {
                    if (data.error) {
                        Vue.toasted.show("Error: " + data.error, {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    } else {
                        var fileExt = file.match(/\.*([0-9a-z]{1,5}$)/);
                        if (!fileExt['index'])
                            fileExt = "file";
                        else
                            fileExt = fileExt[1];
                        UserConfig.terminalFileManagerControlsEnabled = false;
                        UserConfig.fileManagerEditorEnabled = true;
                        UserConfig.fileManagerEditorPath = file;
                        UserConfig.fileManagerEditorFileOwner = data.fileowner;
                        $('#fileManagerEditor').fadeIn();
                        fileManagerEditorFlask = new CodeFlask('#fileManagerEditor', {
                            language: fileExt,
                            lineNumbers: true,
                        });
                        fileManagerEditorFlask.updateCode(Base64.decode(data.output));
                    }
                    $('#terminalDimmer').fadeOut(100);
                })
                .fail(function (err) {
                    Vue.toasted.show("Error: " + err, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                    $('#terminalDimmer').fadeOut(100);
                });
        },
        base64encodefile: function (str) {
            return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
                function toSolidBytes(match, p1) {
                    return String.fromCharCode('0x' + p1);
                }));
        },
        base64decodefile: function (str) {
            return decodeURIComponent(atob(str).split('').map(function (c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));
        },
        fileManagerEditorSave: function () {
            $('#terminalDimmer').fadeIn(100);
            var jqxhr = $.get("../../terminal/?type=sftp&command=put&path=" + UserConfig.fileManagerEditorPath + "&data=" + Base64.encode(fileManagerEditorFlask.getCode().replace(/[\r]/g, '')), function () {

                })
                .done(function (data) {
                    if (!data.success) {
                        $('#terminalDimmer').fadeOut(100);
                        Vue.toasted.show("Error: saving file failed", {
                            position: "top-right",
                            singleton: true,
                            duration: 3000,
                            action: {
                                text: 'Cancel',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    } else {
                        UserConfig.fileManagerEditorEnabled = false;
                        UserConfig.terminalFileManagerControlsEnabled = true;
                        UserConfig.fileManagerEditorPath = "";
                        $('#fileManagerEditor').fadeOut();
                        $('#terminalDimmer').fadeOut(100);
                        Vue.toasted.show("File Saved", {
                            position: "top-right",
                            singleton: true,
                            duration: 3000,
                            action: {
                                text: 'Cancel',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    }
                })
                .fail(function (err) {
                    $('#terminalDimmer').fadeOut(100);
                    Vue.toasted.show("Request Error", {
                        position: "top-right",
                        singleton: true,
                        duration: 3000,
                        action: {
                            text: 'Cancel',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });
            $('.codeflask').remove();
            console.log(Base64.encode(fileManagerEditorFlask.getCode()));
            console.log(encodeURI(Base64.encode(fileManagerEditorFlask.getCode())));
        },
        fileManagerEditorDiscard: function () {
            $('#terminalDimmer').fadeIn(100);
            $('#fileManagerEditor').fadeOut(500, function () {
                UserConfig.fileManagerEditorEnabled = false;
                UserConfig.terminalFileManagerControlsEnabled = true;
                UserConfig.fileManagerEditorPath = "";
                $('#terminalDimmer').fadeOut(100);
                $('.codeflask').remove();
            });

        },
        fileManagerEditorDownload(file, filename) {
            $('#terminalDimmer').fadeIn(100);
            var jqxhr = $.get("../../terminal/?type=sftp&command=get&path=" + file, function (filename) {

                })
                .done(function (data) {
                    if (data.error) {
                        Vue.toasted.show("Error: " + data.error, {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    } else {
                        downloadFile(filename, atob(data.output));
                        Vue.toasted.show(filename + " downloaded.", {
                            position: "top-right",
                            singleton: true,
                            duration: 3000,
                            action: {
                                text: 'Ok',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    }
                    $('#terminalDimmer').fadeOut(100);
                })
                .fail(function (err) {
                    Vue.toasted.show("Error: " + err, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                    $('#terminalDimmer').fadeOut(100);
                });
        },
        fileManagerEditorDelete(file) {
            UserConfig.fileManagerEditorDelete = UserConfig.fileManagerWD + "/" + file;
            Vue.toasted.show("Delete file?", {
                position: "top-right",
                singleton: true,
                duration: 15000,
                action: [{
                        text: 'Cancel',
                        onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                            return
                        }
                    },
                    {
                        text: 'Delete',
                        onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                            var jqxhr = $.get("../../terminal/?type=sftp&command=delete&path=" + UserConfig.fileManagerEditorDelete, function () {


                                    loading();
                                })
                                .done(function (data) {
                                    Vue.toasted.show(data.message, {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 3000,
                                        action: {
                                            text: 'Ok',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                })
                                .fail(function (err) {
                                    Vue.toasted.show("Request Error", {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 3000,
                                        action: {
                                            text: 'Ok',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                });
                        }
                    }
                ],
            });
        },
        toggleBlockedAttempts: () => {
            this.UserConfig.blockedAttemptsEnabled = !this.UserConfig.blockedAttemptsEnabled
        }

    },
    computed: {
        blockedAttemptsEnabled: () => {
            return this.UserConfig.blockedAttemptsEnabled
        },
        serverSelectionEnabled: function () {
            return this.UserConfig.serverSelectionEnabled
        },
        servers: function () {
            return this.UserConfig.servers
        },
        isterminalExpanded: function () {
            return this.UserConfig.terminalExpanded
        },

        terminalFileManagerEnabled: function () {
            return this.UserConfig.terminalFileManagerEnabled
        },
        fileManagerOwnerUid: function () {
            return UserConfig.fileManagerList["."].uid;
        },
        fileManagerUploadEnabled: function () {
            return this.UserConfig.fileManagerUploadEnabled
        },
        fileManagerJobTemplateEnabled: function () {
            return this.UserConfig.fileManagerJobTemplateEnabled
        },
        fileManagerEnabled: function () {
            return this.UserConfig.fileManagerEnabled && this.UserConfig.terminalFileManagerEnabled
        },
        fileManagerEditorPath: function () {
            return this.UserConfig.fileManagerEditorPath
        },
        fileManagerEditorContent: function () {
            return fileManagerEditorFlask.getCode();
        },
        fileManagerEditorFileOwner: function () {
            return UserConfig.fileManagerEditorFileOwner
        },
        fileManagerEditorEnabled: function () {
            return this.UserConfig.fileManagerEditorEnabled
        },
        terminalEnabled: function () {
            return this.UserConfig.terminalEnabled && this.UserConfig.terminalFileManagerEnabled
        },
        fileManagerList: function () {
            return this.UserConfig.fileManagerList
        },
        fileManagerWD: function () {
            return this.UserConfig.fileManagerWD;
        },
        expandIcon: function () {
            if (this.UserConfig.terminalExpanded) {
                return 'compress icon'
            } else {
                return 'expand icon'
            }
        },
        LoggedInUser: function () {
            return root.LoggedInUser
        },
        siteName: function () {
            return root.SiteConfig.config.site_name
        },
        userstrend: function () {
            this.listUsers;
            var userstrendarray = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1];
            for (let i = 0; i < this.UsersData.users.length; i++) {
                userstrendarray[new Date(this.UsersData.users[i].joining_date).getMonth()]++;
            }
            return userstrendarray
        },
        usersCount: function () {
            return this.UsersData.users.length
        },
        isAdminEnabled: function () {
            return root.LoggedInUser.userIsAdmin;
        },

    },
    asyncComputed: {
        failsCount() {
            return Vue.http.get('../../controller/api.php?a=failsCount')
                .then(response => response.data.failsCount, response => 'err')
        },
        sessionsCount() {
            return Vue.http.get('../../controller/api.php?a=sessionsCount')
                .then(response => response.data.sessionsCount, response => 'err')
        },
        blockedAttempts() {
            return Vue.http.get('../../controller/api.php?a=blockedAttempts')
                .then(response => {
                    $('i').popup();
                    return response.data.blockedAttempts.reverse();
                }, response => 'err')
        },
        onlineUsersCount() {
            return Vue.http.get('../../controller/api.php?a=onlineUsersCount')
                .then(response => response.data.onlineUsersCount, response => 'err')
        },
        AICount() {
            return Vue.http.get('../../controller/api.php?a=AICount')
                .then(response => response.data.AICount, response => 'err')
        }
    }
};
var Users = {
    template: '#users',
    data: function () {
        return UsersData
    },
    mounted: function () {
        this.listUsers;
    },
    created: function () {
        $.get("../../controller/api.php?a=listGroups", function (data) {
            UsersData.groupsList = data.listGroups;
        });

    },
    computed: {
        filteredUserData: function () {
            return UsersData.users.filter(user => {
                if (user.name.toLowerCase().indexOf(this.searchUsersInput.toLowerCase()) > -1 || user.email.toLowerCase().indexOf(this.searchUsersInput.toLowerCase()) > -1 || user.username.toLowerCase().indexOf(this.searchUsersInput.toLowerCase()) > -1) {
                    return user
                }
            })
        },
        joiningDateGMT: function () {
            return getGMT(UsersData.selectedUser.user.joining_date);
        },
        treeViewSessionData: function () {
            return treeViewUserSession(userSessionInfo)
        },
        usersActivity: function () {
            return UsersData.selectedUser.activity
        },
        browserRecords: function () {
            return Menu.browserRecords
        },
        browserRecordsEnabled: function () {
            return Menu.browserRecordsEnabled
        },
        loginRecords: function () {
            return Menu.loginRecords
        },
        loginRecordsEnabled: function () {
            return Menu.loginRecordsEnabled
        },
        loginRecordsTimestamp: function () {
            return Menu.loginRecordsTimestamp
        },
        loginFrequencyTrend: function () {
            return Menu.selectedUserTrends.loginFrequency
        },
        failedLoginsTrend: function () {
            return Menu.selectedUserTrends.failedLogins
        },
        browserFrequencyTrend: function () {
            return Menu.selectedUserTrends.browserFrequency
        },
        loginRecordsShowMore: function () {
            return Menu.loginRecords.length > this.loginRecordsN
        },
        loginRecordsN: function () {
            return Menu.loginRecordsN
        },
        isUserOnline: function () {
            return !((new Date() - new Date(UsersData.selectedUser.user.lastOnline)) / 1000 > 70)
        },
        userSinceLastOnline: function () {
            if (((new Date() - new Date(UsersData.selectedUser.user.lastOnline)) / 1000) < 0) {
                return 'Now'
            } else {
                return timeSince(new Date(this.selectedUser.user.lastOnline)) + ' Ago'
            };
        },
        selectedUserGroup: function () {
            var g;
            this.groupsList.forEach(function (e) {
                e.id == parseInt(UsersData.selectedUser.user.group) ? g = e.name : g = "Not assigned"
            })
            return g;
        },


    },
    methods: {
        listUsers: function () {
            var jqxhr = $.get("../../controller/api.php?a=listUsers", function () {

                })
                .done(function (data) {
                    UsersData.users = data.users;
                })
                .fail(function () {
                    Vue.toasted.show("Error: " + data, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });
            UsersData.isInUserViewMode = false;
        },
        sinceLastOnline: function (date) {
            if (((new Date() - new Date(date)) / 1000) < 0) {
                return 'Now'
            } else {
                return timeSince(new Date(date)) + ' Ago'
            };
        },
        syncUsers: function () {
            if (this.syncingUsers.synced || this.syncingUsers.syncing) {
                return
            }
            this.syncingUsers.syncing = true;
            var jqxhr = $.get("../../controller/api.php?a=syncUsers", function () {})
                .done(function (data) {
                    Vue.toasted.show(data.output, {
                        position: "bottom-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                    UsersData.syncingUsers.syncing = false;
                    UsersData.syncingUsers.synced = true;

                })
                .fail(function (err) {
                    UsersData.syncingUsers.syncing = false;
                    Vue.toasted.show("Error: " + err, {
                        position: "bottom-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });

        },
        createSystemUser: function () {
            initLoader("#userDetails");
            var jqxhr = $.get("../../controller/api.php?a=createSystemUser&u=" + UsersData.selectedUser.user.id, function () {})
                .done(function (data) {
                    Vue.toasted.show(data.output, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                    initLoader();

                })
                .fail(function (err) {
                    initLoader();
                    Vue.toasted.show("Error: " + err, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });
            this.viewUser(UsersData.selectedUser.user);

        },
        removeSystemUser: function () {
            initLoader("#userDetails");
            Vue.toasted.show("Remove system user? ", {
                position: "top-right",
                singleton: true,
                duration: 60000,
                action: [{
                        text: 'User & Home folder',
                        onClick: (e, toastObject) => {
                            var jqxhr = $.get("../../controller/api.php?a=removeSystemUser&removeHome=1&u=" + UsersData.selectedUser.user.id, function () {})
                                .done(function (data) {
                                    Vue.toasted.show(data.output, {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 10000,
                                        action: {
                                            text: 'Close',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                    initLoader();

                                })
                                .fail(function (err) {
                                    initLoader();
                                    Vue.toasted.show("Error: " + err, {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 10000,
                                        action: {
                                            text: 'Close',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                });
                            toastObject.goAway(0);
                        }
                    },
                    {
                        text: 'Just user',
                        onClick: (e, toastObject) => {
                            var jqxhr = $.get("../../controller/api.php?a=removeSystemUser&u=" + UsersData.selectedUser.user.id, function () {})
                                .done(function (data) {
                                    Vue.toasted.show(data.output, {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 10000,
                                        action: {
                                            text: 'Close',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                    initLoader();

                                })
                                .fail(function (err) {
                                    initLoader();
                                    Vue.toasted.show("Error: " + err, {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 10000,
                                        action: {
                                            text: 'Close',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                });
                            toastObject.goAway(0);
                        }
                    },
                    {
                        text: 'Cancel',
                        onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                            initLoader();
                        }
                    }
                ],
            });

        },
        toggleUserActive: function () {
            Vue.toasted.show("Change activation state?", {
                position: "top-right",
                singleton: true,
                duration: 10000,
                action: [{
                        text: 'Yes',
                        onClick: (e, toastObject) => {
                            initLoader("#userDetails");
                            var jqxhr = $.get("../../controller/api.php?a=toggleUserActive&u=" + UsersData.selectedUser.user.id, function () {})
                                .done(function (data) {
                                    if (!data.error) {
                                        UsersData.selectedUser.user.isactive = data["isActive"];
                                        //console.log(data["isActive"]);
                                    } else {
                                        Vue.toasted.show("Error: " + data.error, {
                                            position: "top-right",
                                            singleton: true,
                                            duration: 10000,
                                            action: {
                                                text: 'Close',
                                                onClick: (e, toastObject) => {
                                                    toastObject.goAway(0);
                                                }
                                            },
                                        });
                                    }
                                    initLoader();
                                })
                                .fail(function (err) {
                                    initLoader();
                                    Vue.toasted.show("Error: " + err, {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 10000,
                                        action: {
                                            text: 'Close',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                });
                            toastObject.goAway(0);
                        }
                    },
                    {
                        text: 'Cancel',
                        onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                        }
                    }
                ],
            });

        },
        deleteUser: function () {
            Vue.toasted.show("Permanently delete user?", {
                position: "top-right",
                singleton: true,
                duration: 3000,
                action: [{
                        text: 'Yes',
                        onClick: (e, toastObject) => {
                            initLoader("#userDetails");
                            var jqxhr = $.get("../../controller/api.php?a=deleteUser&u=" + UsersData.selectedUser.user.id, function () {})
                                .done(function (data) {
                                    if (!data.error) {
                                        Vue.toasted.show("User successfully deleted.", {
                                            position: "top-right",
                                            singleton: true,
                                            duration: 10000,
                                            action: {
                                                text: 'Close',
                                                onClick: (e, toastObject) => {
                                                    toastObject.goAway(0);
                                                }
                                            },
                                        });
                                        //console.log(data["isActive"]);
                                    } else {
                                        Vue.toasted.show("Error: " + data.error, {
                                            position: "top-right",
                                            singleton: true,
                                            duration: 10000,
                                            action: {
                                                text: 'Close',
                                                onClick: (e, toastObject) => {
                                                    toastObject.goAway(0);
                                                }
                                            },
                                        });
                                    }
                                    initLoader();
                                })
                                .fail(function (err) {
                                    initLoader();
                                    Vue.toasted.show("Error: " + err, {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 10000,
                                        action: {
                                            text: 'Close',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                });
                            toastObject.goAway(0);
                        }
                    },
                    {
                        text: 'Cancel',
                        onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                        }
                    }
                ],
            });

        },
        viewUser: function (user) {
            this.isInUserViewAPIPermissions = false;
            this.isInUserView2FAMode = false;
            this.isInUserViewActivityMode = false;
            this.isInUserMonitorMode = false;
            initLoader("#userDetails");
            UsersData.isInUserViewMode = true;
            UsersData.selectedUser = {
                user
            };
            var jqxhr = $.get("../../controller/api.php?a=viewUser&u=" + UsersData.selectedUser.user.id, function () {})
                .done(function (data) {
                    if (!data.error) {
                        UsersData.selectedUser.user = data.userDetails;
                        Users.methods.apiAccess(UsersData.selectedUser.user.id);
                    } else {
                        Vue.toasted.show("Error: " + data, {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    }

                    initLoader();
                })
                .fail(function (err) {
                    initLoader();
                    Vue.toasted.show("Error: " + err, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });

        },
        apiAccess: function (user) {
            var jqxhr = $.get("../../controller/api.php?a=apiAccess&u=" + UsersData.selectedUser.user.id, function () {})
                .done(function (data) {
                    if (!data.error) {
                        UsersData.selectedUser.user.api = {};
                        UsersData.selectedUser.user.api.apiAccessAvailable = JSON.parse(data.apiAccessAvailable);
                        if (!data.apiAccess) {
                            UsersData.selectedUser.user.api.apiAccess = [];
                        } else {
                            UsersData.selectedUser.user.api.apiAccess = JSON.parse(data.apiAccess.actions);
                        }
                    } else {
                        Vue.toasted.show("Error: " + data, {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    }
                })
                .fail(function (err) {
                    Vue.toasted.show("Error: " + err, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });
        },
        apiAccessDropdown: () => {
            setTimeout(() => {
                $('.ui.dropdown').dropdown("set selected", UsersData.selectedUser.user.api.apiAccess);
            }, 300)
        },
        apiAccessUpdate: () => {
            Vue.toasted.show("Giving API access might expose sensitive information, and grant them system actions. Change user's permission?", {
                position: "top-right",
                singleton: true,
                duration: 10000,
                action: [{
                        text: 'Yes',
                        onClick: (e, toastObject) => {
                            initLoader("#userDetails");
                            var jqxhr = $.get("../../controller/api.php?a=apiUpdateAccess&u=" + UsersData.selectedUser.user.id + "&actions=" + JSON.stringify($('.ui.dropdown.apiAccess').dropdown("get value")), function () {})
                                .done(function (data) {
                                    if (!data.error) {
                                        Vue.toasted.show(data.result, {
                                            position: "top-right",
                                            singleton: true,
                                            duration: 5000,
                                        })
                                    } else {
                                        Vue.toasted.show("Error: " + data.error, {
                                            position: "top-right",
                                            singleton: true,
                                            duration: 10000,
                                            action: {
                                                text: 'Close',
                                                onClick: (e, toastObject) => {
                                                    toastObject.goAway(0);
                                                }
                                            },
                                        });
                                    }
                                    initLoader();
                                })
                                .fail(function (err) {
                                    initLoader();
                                    Vue.toasted.show("Error: " + err, {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 10000,
                                        action: {
                                            text: 'Close',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                });
                            toastObject.goAway(0);
                        }
                    },
                    {
                        text: 'Cancel',
                        onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                        }
                    }
                ],
            });
        },
        sessionExpire: function () {
            var jqxhr = $.get("../../controller/api.php?a=viewUser&u=" + UsersData.selectedUser.user.id, function () {})
                .done(function (data) {
                    if (!data.error) {
                        UsersData.selectedUser.user.sessionExpire = data.sessionExpire;
                    } else {
                        UsersData.selectedUser.user.sessionExpire = null;
                    }
                })
                .fail(function (err) {
                    UsersData.selectedUser.user.sessionExpire = null;
                });
        },
        getBrowserRecords: function () {
            var jqxhr = $.get("../../controller/api.php?a=viewUserBrowserRecords&u=" + UsersData.selectedUser.user.id, function () {
                    loading();
                })
                .done(function (data) {
                    loading(false);
                    if (!data.error) {
                        Menu.browserRecords = data.browserRecords;
                        Menu.browserRecordsEnabled = true;
                        data.browserRecords.forEach(i => {
                            Menu.selectedUserTrends.browserFrequency.unshift(parseInt(i.loginCount));
                        });
                    } else {
                        Vue.toasted.show("Error: " + data.error, {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    }
                })
                .fail(function (err) {
                    loading(false);
                    Vue.toasted.show("Error: " + err, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });
        },
        getActivity: function () {
            var jqxhr = $.get("../../controller/api.php?a=viewUserActivity&u=" + UsersData.selectedUser.user.id, function () {})
                .done(function (data) {

                    if (!data.error) {

                        UsersData.selectedUser.activity = data.activity;
                        var regex = /\[[a-zA-Z]*\]/g; //regex to find e.g [Security]
                        UsersData.selectedUser.activity.forEach(function (e, i) {
                            var m = e.comment.match(regex);
                            if (m != null)
                                m.forEach(function (c) {
                                    UsersData.selectedUser.activity[i].comment.replace(c, "<span>" + c + "</span>");
                                })
                        });
                        UsersData.isInUserViewActivityMode = true;
                    } else {
                        Vue.toasted.show("Error: " + data.error, {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    }
                })
                .fail(function (err) {
                    Vue.toasted.show("Error: " + err, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });
        },
        getLoginRecords: function () {
            var jqxhr = $.get("../../controller/api.php?a=viewUserLoginRecords&u=" + UsersData.selectedUser.user.id, function () {
                    loading();
                })
                .done(function (data) {
                    loading(false);
                    if (!data.error) {
                        Menu.loginRecords = data.loginRecords;
                        Menu.loginRecordsTimestamp = {};
                        Menu.loginRecordsTimestamp.success = [];
                        Menu.loginRecordsTimestamp.daysHrs = [
                            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                        ];
                        Menu.loginRecordsEnabled = true;
                        data.loginRecords.forEach(i => {
                            /*Logins per Month Trend
                            var d = new Date(i.timestamp);
                            var n = d.getMonth()+1;
                            Menu.selectedUserTrends.loginFrequency.push(n);
                            */
                            Menu.selectedUserTrends.failedLogins.unshift(parseInt(i.fails));
                            i.fails == "0" ? Menu.loginRecordsTimestamp.success.push(i.timestamp) : 0;
                        });
                        Menu.loginRecordsTimestamp.success.forEach((e) => {
                            var t = new Date(e);
                            Menu.loginRecordsTimestamp.daysHrs[t.getDay()][t.getHours()]++;
                            var nextDay;
                            var nextHour;
                            if (t.getHours() == 23) {
                                nextHour = 0;
                                nextDay = t.getDay() == 6 ? 0 : t.getDay() + 1;
                            } else {
                                nextHour = t.getHours() + 1;
                                nextDay = t.getDay();
                            }
                            Menu.loginRecordsTimestamp.daysHrs[nextDay][nextHour] = Menu.loginRecordsTimestamp.daysHrs[nextDay][nextHour] + 0.5;
                            //number*largestnumber/totalsum
                        })
                        //Menu.selectedUserTrends.loginFrequency = [5,4,7,2,10,11,12,7,3,1]
                    } else {
                        Vue.toasted.show("Error: " + data.error, {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    }
                })
                .fail(function (err) {
                    loading(false);
                    Vue.toasted.show("Error: " + err, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });
        },
        closeBrowserRecords: function () {
            Menu.browserRecordsEnabled = !this.browserRecordsEnabled
        },
        closeActivity: function () {
            this.isInUserViewActivityMode = !this.isInUserViewActivityMode
        },
        closeLoginRecords: function () {
            Menu.loginRecordsEnabled = !this.loginRecordsEnabled
        },
        toggleLoginRecordsShowMore: function () {
            Menu.loginRecordsN = Menu.loginRecordsN + 5;
            //Menu.loginRecordsShowMore = !this.loginRecordsShowMore
        },
        toggleShowGroups: function () {
            UsersData.showGroups = !UsersData.showGroups;
        },
        deleteGroup: function (group) {
            UsersData.deleteGroup = group;
            Vue.toasted.show("Delete group " + UsersData.deleteGroup.name + "?", {
                position: "top-right",
                singleton: true,
                duration: 3000,
                action: [{
                        text: 'Yes',
                        onClick: (e, toastObject) => {
                            initLoader("#usersContent");
                            var jqxhr = $.get("../../controller/api.php?a=deleteGroup&id=" + UsersData.deleteGroup.id, function () {})
                                .done(function (data) {
                                    if (!data.error) {
                                        $.get("../../controller/api.php?a=listGroups", function (data) {
                                            UsersData.groupsList = data.listGroups;
                                        });
                                        Vue.toasted.show("Group Deleted", {
                                            position: "top-right",
                                            singleton: true,
                                            duration: 10000,
                                            action: {
                                                text: 'Close',
                                                onClick: (e, toastObject) => {
                                                    toastObject.goAway(0);
                                                }
                                            },
                                        });
                                    } else {
                                        Vue.toasted.show("Error: " + data.error, {
                                            position: "top-right",
                                            singleton: true,
                                            duration: 10000,
                                            action: {
                                                text: 'Close',
                                                onClick: (e, toastObject) => {
                                                    toastObject.goAway(0);
                                                }
                                            },
                                        });
                                    }
                                    initLoader();
                                })
                                .fail(function (err) {
                                    initLoader();
                                    Vue.toasted.show("Error: " + err, {
                                        position: "top-right",
                                        singleton: true,
                                        duration: 10000,
                                        action: {
                                            text: 'Close',
                                            onClick: (e, toastObject) => {
                                                toastObject.goAway(0);
                                            }
                                        },
                                    });
                                });
                            toastObject.goAway(0);
                        }
                    },
                    {
                        text: 'No',
                        onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                        }
                    }
                ],
            });
        },
        createGroup: function () {
            var name = this.createGroupName;
            for (var i = this.groupsList.length; i--;) {
                if (this.groupsList[i].name.toLowerCase() == name.toLowerCase()) {
                    name = '';
                }
            }
            if (name == "" || name.length <= 4) {
                this.createGroupName = "";
                Vue.toasted.show("Error, Check group name.", {
                    position: "top-right",
                    singleton: true,
                    duration: 3000,
                    action: {
                        text: 'Close',
                        onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                        }
                    },
                });
            } else {
                Vue.toasted.show("Create group " + name + "?", {
                    position: "top-right",
                    singleton: true,
                    duration: 3000,
                    action: [{
                            text: 'Yes',
                            onClick: (e, toastObject) => {
                                initLoader("#usersContent");
                                var jqxhr = $.get("../../controller/api.php?a=createGroup&groupName=" + UsersData.createGroupName, function () {})
                                    .done(function (data) {
                                        if (!data.error) {
                                            $.get("../../controller/api.php?a=listGroups", function (data) {
                                                UsersData.groupsList = data.listGroups;
                                            });
                                            groupCreationEnabled = false;
                                            Vue.toasted.show("Group Created", {
                                                position: "top-right",
                                                singleton: true,
                                                duration: 10000,
                                                action: {
                                                    text: 'Close',
                                                    onClick: (e, toastObject) => {
                                                        toastObject.goAway(0);
                                                    }
                                                },
                                            });
                                        } else {
                                            Vue.toasted.show("Error: " + data.error, {
                                                position: "top-right",
                                                singleton: true,
                                                duration: 10000,
                                                action: {
                                                    text: 'Close',
                                                    onClick: (e, toastObject) => {
                                                        toastObject.goAway(0);
                                                    }
                                                },
                                            });
                                        }
                                        initLoader();
                                    })
                                    .fail(function (err) {
                                        initLoader();
                                        Vue.toasted.show("Error: " + err, {
                                            position: "top-right",
                                            singleton: true,
                                            duration: 10000,
                                            action: {
                                                text: 'Close',
                                                onClick: (e, toastObject) => {
                                                    toastObject.goAway(0);
                                                }
                                            },
                                        });
                                    });
                                toastObject.goAway(0);
                            }
                        },
                        {
                            text: 'No',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        }
                    ],
                });
            }
        }
    }
};
var Settings = {
    template: '#settings',
    data: function () {
        return {
            oldSiteConfig: {},
            UserConfig
        }
    },
    computed: {
        menu: function () {
            return this.$parent.menu
        },
        menuLength: function () {
            return digitToWord(this.$parent.menu.length)
        },
        LoggedInUser: function () {
            return root.LoggedInUser
        },
        config: function () {
            return root.SiteConfig.config
        },
        logs: function () {
            return JSON.parse(root.SiteConfig.config.logs);
        },
        servers: function () {
            return UserConfig.servers
        }
    },
    mounted: function () {
        $('.menu.settingsConfig .item').tab();
        this.oldSiteConfig = root.SiteConfig.config;
    },
    methods: {

        saveConfig: function () {
            console.log(this.oldSiteConfig)
            $.each(root.SiteConfig.config, function (index, value) {
                if (value == this.oldSiteConfig[index])
                    delete root.SiteConfig.config[index];
            });
        }
    }
};
var About = {
    template: '#about'
};
var Logs = {
    template: '#logs',
    data: function () {
        return UsersData
    },
    mounted: function () {
        this.logRequests();

    },
    computed: {
        requests: function () {
            return logs.requests
        }
    },
    methods: {
        unixToDate: function (t) {
            return timeConverter(t);
        },
        logRequests: function () {
            var jqxhr = $.get("../../controller/api.php?a=requestsLogs&n=" + this.logs.numberOfRequests, function () {
                    loading();
                })
                .done(function (data) {
                    loading(false);
                    if (!data.error) {
                        try {
                            UsersData.logs.requests = JSON.parse(data.requestsLogs);
                        } catch (err) {
                            Vue.toasted.show("Error: " + err, {
                                position: "top-right",
                                singleton: true,
                                duration: 10000,
                                action: {
                                    text: 'Close',
                                    onClick: (e, toastObject) => {
                                        toastObject.goAway(0);
                                    }
                                },
                            });
                        }


                    } else {
                        Vue.toasted.show("Error: " + data.error, {
                            position: "top-right",
                            singleton: true,
                            duration: 10000,
                            action: {
                                text: 'Close',
                                onClick: (e, toastObject) => {
                                    toastObject.goAway(0);
                                }
                            },
                        });
                    }
                })
                .fail(function (err) {
                    loading(false);
                    Vue.toasted.show("Error: " + err, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });
        }
    },


};
var Ai = {
    data: function () {
        return {
            Calc_fp: undefined,
            Calc_s: undefined,
            Calc_f: undefined,
            Calc_l: undefined,
            Calc_allow: undefined
        }
    },
    mounted: function () {
        $('.control i').popup();
    },
    methods: {
        CalculateProb: function () {
            var net = new brain.NeuralNetwork();
            net.train(BrainData);
            this.Calc_allow = net.run({
                fp: this.Calc_fp,
                s: this.Calc_s,
                f: this.Calc_f,
                l: this.Calc_l
            }).allow;
            Vue.toasted.show("Calculated: " + this.Calc_allow, {
                position: "top-right",
                singleton: true,
                duration: 3000,
                action: {
                    text: 'Close',
                    onClick: (e, toastObject) => {
                        toastObject.goAway(0);
                    }
                },
            });
        }
    },
    template: '#ai',
    computed: {}
};
var Visualize = {
    data: function () {
        return {
            loginRecordsTimestamp: {
                success: [],
                daysHrs: []
            },
        }
    },
    mounted: function () {

    },
    methods: {
        loginActivity: function () {
            var self = this;
            self.success = [1, 2, 3];
            try {
                var jqxhr = $.get("../../controller/api.php?a=viewLoginAttempts")
                    .done((data) => {
                        if (!data.error) {
                            console.log(self);
                            this.loginRecordsTimestamp.daysHrs = [
                                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                            ];
                            data.loginAttempts.forEach(i => {
                                i.fails == "0" ? this.loginRecordsTimestamp.success.push(i.timestamp) : 0;
                            });
                            this.loginRecordsTimestamp.success.forEach((e) => {
                                var t = new Date(e);
                                this.loginRecordsTimestamp.daysHrs[t.getDay()][t.getHours()]++;
                                var nextDay;
                                var nextHour;
                                if (t.getHours() == 23) {
                                    nextHour = 0;
                                    nextDay = t.getDay() == 6 ? 0 : t.getDay() + 1;
                                } else {
                                    nextHour = t.getHours() + 1;
                                    nextDay = t.getDay();
                                }
                                this.loginRecordsTimestamp.daysHrs[nextDay][nextHour] = this.loginRecordsTimestamp.daysHrs[nextDay][nextHour] + 0.5;
                                //number*largestnumber/totalsum
                            })
                            //Menu.selectedUserTrends.loginFrequency = [5,4,7,2,10,11,12,7,3,1]
                        }
                    })
            } catch (err) {
                Vue.toasted.show("Error: " + err, {
                    position: "top-right",
                    singleton: true,
                    duration: 30000,
                    action: {
                        text: 'Close',
                        onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                        }
                    },
                });
            }
        }
    },
    template: '#visualize',
    computed: {}
};
var Nope = {
    template: '#nope'
};
Vue.component('routing', {
    template: '#routing',
    computed: {

    }
});

// 2. Define some routes
var routes = [{
        name: 'home',
        path: '/home',
        component: Home
    }, {
        name: 'users',
        path: '/users',
        component: Users
    }, {
        name: 'settings',
        path: '/settings',
        component: Settings
    }, {
        name: 'about',
        path: '/about',
        component: About
    }, {
        name: 'ai',
        path: '/ai',
        component: Ai
    }, {
        name: 'visualize',
        path: '/visualize',
        component: Visualize
    },
    {
        name: 'logs',
        path: '/logs',
        component: Logs
    },
    {
        name: 'nope',
        path: '/nope',
        component: Nope
    }, {
        path: '*',
        redirect: {
            name: 'home'
        }
    }
];

// 3. Create the router instance and pass the `routes` option
var router = new VueRouter({
    routes // short for routes: routes,
});



// 4. Create and mount the root instance.
var Menu = new Vue({
    router,
    data: {
        menu: [{
                text: 'home',
                url: '#/',
                isActive: true,
                iconClass: 'home icon',
                show: true
            },
            {
                text: 'users',
                url: '#/users',
                isActive: false,
                iconClass: 'user icon',
                show: false
            },
            {
                text: 'visualize',
                url: '#/visualize',
                isActive: false,
                iconClass: 'eye icon',
                show: false
            },
            {
                text: 'ai',
                url: '#/ai',
                isActive: false,
                iconClass: 'bullseye icon',
                show: false
            },
            {
                text: 'logs',
                url: '#/logs',
                isActive: false,
                iconClass: 'file alternate outline icon',
                show: false
            },
            {
                text: 'settings',
                url: '#/settings',
                isActive: false,
                iconClass: 'setting icon',
                show: false
            },
            {
                text: 'about',
                url: '#/about',
                isActive: false,
                iconClass: 'info icon',
                show: true
            },

        ],
        userModal: false,
        selectedUser: {},
        selectedUserIcon: '',
        selectedUserImage: '',
        selectedUserTrends: {
            loginFrequency: [],
            failedLogins: [],
            browserFrequency: []
        },
        browserRecordsEnabled: false,
        loginRecordsEnabled: false,
        loginRecords: {},
        loginRecordsShowMore: false,
        loginRecordsN: 5,
    },
    mounted: function () {

    },
    created: function () {

        this.listServers();

    },
    methods: {

        listServers: function () {
            var jqxhr = $.get("../../controller/api.php?a=listServers&enabled=1", function () {

                })
                .done(function (data) {
                    UserConfig.servers = data.servers;
                })
                .fail(function () {
                    Vue.toasted.show("Error: " + data, {
                        position: "top-right",
                        singleton: true,
                        duration: 10000,
                        action: {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        },
                    });
                });
        },
        closeModal: function () {
            $('#userModal').modal('hide');
            this.userModal = false;
        },
    },
    computed: {
        LoggedInUser: function () {
            return root.LoggedInUser
        },
    }
}).$mount('#content');

// Now the app has started!
