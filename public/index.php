<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 'OFF' );


require './access/vendor/autoload.php';
require './access/loginConfig.php';
if (!$auth->isLogged()) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ./access/logout.php");
    exit();
}

require './tools/tracy/src/tracy.php';
    use Tracy\Debugger;
$isAdmin = $auth->isAdmin($_COOKIE['authID']);
if($isAdmin){
    
    Debugger::$logSeverity = E_NOTICE | E_WARNING;
    Debugger::enable(Debugger::DEVELOPMENT, $_SERVER['DOCUMENT_ROOT'].'/system/log/');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/semantic.css">
    <link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">
    <link rel="stylesheet"
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">

    <link rel="stylesheet" href="./assets/css/animate.css">
    <link rel="stylesheet/less" href="./assets/css/styles.less">
    <style type="text/less">

    </style>
    <script type="text/javascript">
        window.onerror = function () {
            alert("Error caught");
        };
    </script>
    <script src="./assets/js/jquery-3.2.1.min.js"></script>
    <script src="./assets/js/semantic.js"></script>
    <script src="./assets/js/less.min.js"></script>
    <script src="./assets/js/vue.js"></script>

    <script src="https://unpkg.com/filepond-plugin-image-preview"></script>
    <script src="https://unpkg.com/filepond"></script>
    <script src="https://unpkg.com/vue-filepond"></script>
    <script src="https://unpkg.com/codeflask/build/codeflask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.0.3/fingerprint2.min.js"></script>

    <script src="./assets/js/qrcode.min.js"></script>
    <script src="https://cdn.rawgit.com/stewartlord/identicon.js/master/identicon.js"></script>
    <script src="./assets/js/sha1.js"></script>
    <script src="./assets/js/functions.js"></script>
    <script src="./assets/js/LoggedInUser.js"></script>
    <script src="https://unpkg.com/vuetrend"></script>
    <script src="./assets/js/vue-bars.min.js"></script>
    <script src="./assets/js/vue-router.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-async-computed@3.5.0/dist/vue-async-computed.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.1"></script>
    <script src="./assets/js/base64.min.js"></script>
    <script src="./assets/js/brain.min.js"></script>
    <script src="./assets/js/brain-neural-network.js"></script>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyCWby3hWZIlk4fKJeRho5WU0ygKNMjwQWQ"></script>
    <script type="text/javascript" src="https://rawgit.com/HPNeo/gmaps/master/gmaps.js"></script>
    <script type="text/javascript" src="./assets/js/map_script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"
        integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>

    <!---AI Stuff -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="./ai/to-svg-browser.js"></script>
    <title>Dashboard</title>

</head>

<body>

    <div id="svgLoader" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #191919;
            z-index: 10;
            transition: top 2s cubic-bezier(0.1, 1.19, 1, 1);
        ">
        <svg version="1.1" id="logo_svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            style="
            width: 50vw;
            height: 70vh;
            position: absolute;
            top: 50%;
            transform: translateY(-50%) translateX(-50%);
            left: 50%;
            right: 0;
        " xml:space="preserve" y="0px" x="0px" viewBox="0 0 612 792">
            <g>

                <path class="m1" fill="#D8C184" d="M307.1,458.1c0-8.7,0-17.4,0-26.1c8.7,0,16.9,0,26,0c0,8.7,0,17.4,0,26.1
        		C324.5,458.1,315.8,458.1,307.1,458.1z"></path>
                <path class="l1" fill="#D8C184" d="M245.1,432.7c8.5,0,16.9,0,25.8,0c0,8.5,0,16.7,0,25.5c-8.6,0-17.2,0-25.8,0
        		C245.1,449.7,245.1,441.2,245.1,432.7z"></path>
                <path class="r1" fill="#D8C184" d="M395.1,458.2c-8.5,0-16.9,0-25.8,0c0-8.6,0-16.8,0-25.5c8.6,0,17.2,0,25.8,0
        		C395.1,441.2,395.1,449.7,395.1,458.2z"></path>
                <path class="t1" opacity="0" fill="#D8C184" d="M320.8,437.2c0,2.9,0,5.8,0,8.8c2.2,0,4.8,0.8,6.1-0.2c1.3-1,1.5-3.6,2.1-5.6c0.2-0.1,0.5-0.1,0.7-0.2
        		c0.3,0.6,0.9,1.3,0.7,1.9c-0.7,3.2-1.5,6.5-2.3,9.7c-0.1,0.5-1,1-1.6,1c-5.1,0.1-10.3,0-15.6,0c-0.9-3.6-1.8-7.1-2.6-10.7
        		c-0.1-0.5,0.5-1.2,0.8-1.8c0.4,0.4,1,0.8,1.1,1.3c1.6,4.8,1.6,4.8,6.8,4.7c0.4,0,0.8-0.1,1.7-0.2c0-3,0-5.9,0-8.9
        		C319.4,437.2,320.1,437.2,320.8,437.2z"></path>
                <path class="l2" fill="#D8C184"
                    d="M302,431.9c0,8.6,0,17.1,0,25.7c-8.5,0-16.9,0-25.6,0c0-8.5,0-17.1,0-25.7C284.8,431.9,293.1,431.9,302,431.9z">
                </path>
                <path class="r2" fill="#D8C184" d="M338.3,431.8c8.7,0,16.8,0,25.4,0c0,8.6,0,17.2,0,25.8c-8.4,0-16.7,0-25.4,0
        		C338.3,449.1,338.3,440.6,338.3,431.8z"></path>
            </g>
        </svg>
    </div>
    <script type="text/javascript" src="./assets/js/svgAnimation.js"></script>



    <div id="loadingIndicator" class="ui dimmer animated fadeIn">
        <div class="ui text loader">Loading</div>
    </div>
    <div id="profileSidebar" class="ui right wide sidebar vertical menu">
        <div class="ui top big attached label item">Profile
            <i onclick="toggleprofileSidebar()" class="icon close"></i>
        </div>


        <div class="ui segment colored">

            <div class="ui top attached label colored heading">User Profile</div>
            <h3 class="ui header">
                <img width=80 height=80 v-bind:src="LoggedInUser.idiconbase64">
                <div class="content">
                    {{LoggedInUser.userFullName}}
                    <div class="sub header">{{LoggedInUser.userRole}}</div>
                </div>
            </h3>

            <form class="ui form">
                <div class="field">
                    <label>Name</label>
                    <input type="text" v-bind:value="LoggedInUser.userFullName">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" v-bind:value="LoggedInUser.userEmail">
                </div>
            </form>
            <hr>
            <a class="ui fluid button disabled">UPDATE</a>
        </div>
        <div class="ui segment colored">

            <div class="ui top attached label colored heading">Two Factor Authentication</div>

            <form class="ui form">
                <div class="field">
                    <label>QR Code</label>
                    <div id="QRCodeImage" class="QRImage"></div>
                </div>
                <div class="field">
                    <label>Secret</label>
                    <input type="text" v-bind:value="LoggedInUser.TFA.Secret" disabled>
                </div>
            </form>
        </div>
        <div v-if="LoggedInUser.API.enabled" class="ui segment colored">

            <div class="ui top attached label colored heading">API</div>

            <form class="ui form">
                <div class="field">
                    <label>Actions Available</label>
                    <textarea type="text" v-bind:value="LoggedInUser.API.actions" disabled></textarea>
                </div>
                <div class="field">
                    <label>Domain</label>
                    <input type="text" v-model="LoggedInUser.API.domain">
                </div>
                <div class="field">
                    <label>API Key</label>
                    <input v-bind:value="LoggedInUser.API.key" type="text" placeholder="API Key is only shown once"
                        disabled>
                </div>
            </form>
            <a @click="generateAPIKey" v-if="LoggedInUser.API.buttonEnabled" class="ui fluid button">GENERATE</a>
        </div>
        <div class="ui segment colored">

            <div class="ui top attached label colored heading">Dashboard</div>
            <form class="ui form">
                <div class="field">
                    <div class="inline field">
                        <div class="ui toggle checkbox">
                            <label class="right floated">Light Theme : {{lightThemeEnabled}}</label>
                            <input v-model="lightThemeEnabled" v-on:change="lightTheme" type="checkbox" tabindex="0">
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <div class="pusher">
        <div id="body">
            <div id="menu" class="animated fadeInDown">
                <div class="ui container">
                    <div class="ui secondary  menu animated fadeInDown">
                        <h3 class="ui item header">
                            <div class="content">
                                <i class="html5 icon"></i>
                                <span>{{siteName}}</span>
                            </div>
                        </h3>

                        <div class="right menu">
                            <a onclick="toggleprofileSidebar()" class="ui item compact button hoverable">
                                <i class="sidebar icon"></i>
                                <span>{{LoggedInUser.userUserName}}</span>
                            </a>
                            <a class="ui item compact button hoverable">
                                <i class="clock outline icon"></i>
                                <span>{{LoggedInUser.timeToExpire}}</span>
                            </a>
                            <confirm @confirm="toggleAdminMode"
                                text="Changing the admin mode will reset open session, continue?">
                                <a v-if="LoggedInUser.userIsAdmin" class="ui item compact button hoverable"
                                    v-bind:class="{ active: isAdminEnabled }">
                                    <i class="spy icon"></i>
                                    <span>Admin</span>
                                </a>
                            </confirm>
                            <confirm @confirm="logout()" text="Logging out will close open session, continue?">
                                <a class="ui item compact button">
                                    <i class="sign out icon"></i>
                                    <span>Logout</span>
                                </a>
                            </confirm>
                        </div>
                    </div>
                </div>
            </div>
            <div id="content">
                <div id="secondMenu" class=" animated fadeInDown">
                    <div class="ui container">
                        <div class="ui secondary  menu">

                            <a v-for="item in menu" v-if="item.show" v-bind:href="item.url"
                                class="secondMenuButtons large ui button "
                                v-bind:class="[$router.currentRoute.name == item.text ? 'active' : '']">
                                <i v-bind:class="item.iconClass"></i>
                                <span>{{item.text}}</span>
                            </a>
                            <!--  <div class="right menu">
                            <div id="languagesDropdown" class="ui item compact basic dropdown button hoverable">
                                <i class="world icon"></i>
                                <span class="text">{{activeLanguage}}</span>
                                <div class="menu">
                                    <div v-for="language in languages" class="item">{{language.language}}</div>
                                </div>
                            </div>
                        </div> -->
                        </div>
                    </div>
                </div>

                <div class="ui container mainContent">
                    <div class="ui black message notification animated fadeInUp hidden">
                        <div class="header"></div>
                        <p></p>
                    </div>

                    <div v-if="!LoggedInUser.userLoaded" class="ui fullpage segment">
                        <div class="ui active dimmer">
                            <div class="ui text loader">Loading</div>
                        </div>
                        <p></p>
                    </div>
                    <keep-alive>
                        <router-view v-if="LoggedInUser.userLoaded"></router-view>
                    </keep-alive>
                </div>
                <div>
                    <div class="ui longer modal userModal" id="userModal">
                        <div class="colored heading header"><img class="ui rounded mini middle aligned image bordered"
                                v-bind:src="selectedUserIcon" v-bind:data-html="selectedUserImage"> User Details <i
                                v-on:click="closeModal" class="close icon right floated "></i></div>
                        <div class="colored content">
                            <div class="ui inverted relaxed divided list">
                                <user-modal v-if="userModal == true"></user-modal>

                            </div>
                        </div>
                        <div class="actions">
                            <div class="ui black deny button"> Nope </div>
                            <div class="ui positive right labeled icon button"> Yep, that's me <i
                                    class="checkmark icon"></i> </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script type="x-template" id="home">
        <div class="">
                <div class="ui stackable grid">
                    <div class="five column row">
                        <div class="column" v-if="isAdminEnabled">
                            <div class="ui segment colored trends stat animated fadeIn">
                                <div class="ui label">Registered Users</div>
                                <p>{{usersCount}}</p>
                            </div>
                        </div>
                        <div class="column" v-if="isAdminEnabled">
                            <div class="ui segment colored trends stat animated fadeIn">
                                <div class="ui label">AI Count</div>
                                <p>{{AICount}}</p>
                            </div>
                        </div>
                        <div class="column" v-if="isAdminEnabled">
                            <div class="ui segment colored trends stat animated fadeIn">
                                <div class="ui label">Failed Logins</div>
                                <p>{{failsCount}}</p>
                            </div>
                        </div>
                        <div class="column" v-if="isAdminEnabled">
                            <div class="ui segment colored trends stat animated fadeIn">
                                <div class="ui label">Active Sessions</div>
                                <p>{{sessionsCount}}</p>
                            </div>
                        </div>
                        <div class="column" v-if="isAdminEnabled">
                            <div class="ui segment colored trends stat animated fadeIn">
                                <div class="ui label">Online Users</div>
                                <p>{{onlineUsersCount}}</p>
                            </div>
                        </div>
                    </div>
                    <div v-if="isAdminEnabled" class="two column row">
                        <div class="column" >
                            <div class="ui segment colored trends stat animated fadeIn">
                                <div class="ui label">Blocked Sources</div>
                                <div class="ui top right attached label control middle"><i @click="toggleBlockedAttempts" data-content="Toggle Blocked Sources" data-variation="inverted mini" v-bind:class="{ plus: !blockedAttemptsEnabled, minus: blockedAttemptsEnabled }" class="square outline icon"></i></div>
                                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                                <table v-if="blockedAttemptsEnabled" class="ui inverted small celled striped table">
                                    <thead>
                                        <tr><th>ID</th>
                                        <th>Source</th>
                                        <th>Expire</th>
                                    </tr></thead>
                                    <tbody>
                                        <tr v-for="item in blockedAttempts">
                                        <td data-label="ID">{{item.id}}</td>
                                        <td data-label="Source">{{item.ip}}</td>
                                        <td data-label="Expire">{{item.expiredate}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </transition>
                            </div>
                        </div>
                        <div class="column" v-if="isAdminEnabled">
                            <div class="ui segment colored trends stat animated fadeIn">
                                <div class="ui label">EMPTY</div>
                                 <div class="ui top right attached label control middle"><i  data-content="Toggle Blocked Sources" data-variation="inverted mini" v-bind:class="{ plus: !blockedAttemptsEnabled, minus: blockedAttemptsEnabled }" class="square outline icon"></i></div>
                    
                            </div>
                        </div>
                        
                    </div>
                    <div id="terminalContainer" class="sixteen wide column">
                        <div id="terminal" class="ui segment colored trends animated fadeInUp">
                            <div id="terminalDimmer" style="display:none" class="ui active dimmer">
                                <div class="ui tiny indeterminate text loader" style="top: 50% !important;">Connecting</div>
                            </div>
                            <div class="ui top left attached label">
                            
                                <div v-if="terminalEnabled || fileManagerEnabled" id="serverSelectionDropdown" class="ui dropdown">(
                                  <span class="text">Select Server</span>
                                  <div class="menu">
                                    <div class="item" value="" v-for="server in servers">{{server.name}}</div>
                                  </div>)
                                </div>
                            <span v-if="!terminalEnabled && !fileManagerEnabled">Terminal & File Manager</span><span v-if="terminalEnabled">Terminal</span><span v-if="fileManagerEnabled && !fileManagerUploadEnabled && !fileManagerJobTemplateEnabled">File Manager</span> <span v-if="fileManagerEnabled && fileManagerUploadEnabled">File Uploader</span> <span v-if="fileManagerEnabled && fileManagerJobTemplateEnabled">Create a Job</span> </div>
                            <div class="ui top right attached label control">
                                <i v-if="terminalEnabled && UserConfig.terminalFileManagerControlsEnabled" @click="initFileManager" data-content="File Manger" data-variation="inverted mini" class="folder open icon"></i>
                                <i v-if="fileManagerEnabled && UserConfig.terminalFileManagerControlsEnabled" @click="initTerminal" data-content="Terminal" data-variation="inverted mini" class="terminal icon"></i>
                                <i v-if="fileManagerEnabled && UserConfig.terminalFileManagerControlsEnabled" @click="initTerminalFileManager" data-content="Refresh" data-variation="inverted mini" class="refresh icon"></i>
                                <i v-if="fileManagerEnabled && UserConfig.terminalFileManagerControlsEnabled" @click="UserConfig.fileManagerJobTemplateEnabled = !fileManagerJobTemplateEnabled; UserConfig.terminalFileManagerControlsEnabled = !UserConfig.terminalFileManagerControlsEnabled" data-content="Create a Job" data-variation="inverted mini" class="gavel icon"></i>
                                <i v-if="fileManagerEnabled && UserConfig.terminalFileManagerControlsEnabled" onclick="updateFilePondSettings()" @click="initFileManagerUploader; UserConfig.fileManagerUploadEnabled = !fileManagerUploadEnabled; UserConfig.terminalFileManagerControlsEnabled = !UserConfig.terminalFileManagerControlsEnabled" data-content="Upload" data-variation="inverted mini" class="upload icon"></i>
                                <i v-if="fileManagerEnabled && (fileManagerUploadEnabled || fileManagerJobTemplateEnabled)" @click="UserConfig.fileManagerUploadEnabled = false; UserConfig.fileManagerJobTemplateEnabled = false; UserConfig.terminalFileManagerControlsEnabled = !UserConfig.terminalFileManagerControlsEnabled" data-content="Close" data-variation="inverted mini"  class="close icon"></i>
                                
                            </div>
                            
                            <div v-if="!terminalFileManagerEnabled" class="ui center aligned overlay segment">
                                <button class="ui button" v-on:click="initTerminalFileManager"><i class="plug icon"></i> Connect</button>
                            </div>
                            
                            
                            
                            <form v-if="terminalEnabled" v-on:submit.prevent="" class="ui form">
                                <div class="ui stackable grid">
                                    <div class="sixteen wide column">
                                        <div class="ui mini labeled fluid input">
                                            <div class="ui terminal label">
                                                <a data-content="Change directory via File Manager" data-variation="inverted mini"><i class="info circle icon"></i></a> {{LoggedInUser.userUserName}}@{{UserConfig.selectedServer}}:{{fileManagerWD ? fileManagerWD : "/home/" + LoggedInUser.userUserName}}$
                                            </div>
                                            <input v-model="CommandInput" v-on:keyup.up="previousHistory" v-on:keyup.down="nextHistory"  @keyup.enter="CommandSubmit" class="terminal" type="text" placeholder="Command to execute">
                                        </div>
                                    </div>
                                    <div class="sixteen wide column">
                                        <div id="CommandOutput" class="ui segment small">

                                        </div>
                                    </div>
                                </div>
                            </form>
                            <form v-on:submit.prevent="onSubmit" v-if="fileManagerEnabled">
                                <div class="ui stackable grid">
                                    <div class="sixteen wide column">
                                        <div v-if="fileManagerUploadEnabled">
                                            <file-pond></file-pond>
                                        </div>
                                        <div v-if="fileManagerJobTemplateEnabled">
                                            <form v-on:submit.prevent="" class="ui form">
                                                <div class="fields">
                                                    <div class="six wide field">
                                                        <label>Job Path</label>
                                                        <input type="text" v-bind:placeholder="fileManagerWD + '/'" disabled>
                                                    </div>
                                                    <div class="ten wide field">
                                                        <label>Job Name</label>
                                                        <input @keyup="fileManagerJobTemplateGenerateCode" type="text" name="job-name" v-model="UsersData.job.name" onkeypress="return /[a-z]/i.test(event.key)" placeholder="Job Name">
                                                    </div>
                                                </div>
                                                <div class="fields">
                                                    <div class="eight wide field">
                                                        <label>Number of Nodes</label>
                                                        <input @change="fileManagerJobTemplateGenerateCode" type="number" v-model="UsersData.job.nodes" min="1" max="7" value="1" placeholder="1">
                                                    </div>
                                                    <div class="eight wide field">
                                                        <label>Number of Processors Per Node</label>
                                                        <input @change="fileManagerJobTemplateGenerateCode" type="number" v-model="UsersData.job.PPN"  min="1" max="4" value="1" placeholder="1">
                                                    </div>
                                                </div>
                                                <div class="fields">
                                                    <div class="six wide field">
                                                        <label>Executable Path</label>
                                                        <input type="text" v-bind:placeholder="fileManagerWD + '/'" disabled>
                                                    </div>
                                                    <div class="ten wide field">
                                                        <label>Executable File Name</label>
                                                        <input @keyup="fileManagerJobTemplateGenerateCode" type="text" v-model="UsersData.job.fileName"  onkeypress="return /[a-z]/i.test(event.key)" placeholder="compiled filename">
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <label>Job File (will be saved as {{fileManagerWD + '/' + UsersData.job.name + '.job'}}):</label>
                                                    <textarea v-model="UsersData.job.jobCode" style="resize: none;" row="5"  resizable="false" disabled>#!/bin/bash &#13;&#10;#PBS -l nodes={{ UsersData.job.nodes }}:ppn={{UsersData.job.PPN}} &#13;&#10;#PBS -N {{UsersData.job.name}} &#13;&#10;nprocs=`wc-l $PBS_NODEFILE | awk'{ print $1 }'` &#13;&#10;mpirun -np {{UsersData.job.nodes * UsersData.job.PPN}} -machinefile $PBS_NODEFILE {{fileManagerWD}}/{{UsersData.job.fileName}}
                                                    </textarea>
                                                    

                                                </div>
                                                <div class="field">
                                                    <button class="ui button" @click="fileManagerJobTemplateSave(true)" >Save and Submit</button>
                                                    <button class="ui button" @click="fileManagerJobTemplateSave(false)" >Save</button>
                                                </div>
                                                
                                                
                                            </form>
                                        </div>
                                        <table v-if="!fileManagerUploadEnabled && !fileManagerJobTemplateEnabled" class="ui inverted small celled striped table">
                                          <thead>
                                            <tr>
                                            <th v-if="!fileManagerEditorEnabled" colspan="2">
                                                {{fileManagerWD}}
                                            </th>
                                            <th v-if="fileManagerEditorEnabled">
                                                (<i class="user icon"></i> {{fileManagerEditorFileOwner}}) {{fileManagerEditorPath}}
                                            </th>
                                            <th v-if="fileManagerEditorEnabled" class="right aligned collapsing"><div @click="fileManagerEditorDiscard" class="ui red basic mini cancel inverted button"><i class="remove icon"></i>Discard</div></th>
                                            <th v-if="fileManagerEditorEnabled" class="right aligned collapsing"><div @click="fileManagerEditorSave" class="ui green basic mini ok inverted button"><i class="save icon"></i>Save</div></th>
                                            <th v-if="!fileManagerEditorEnabled" class="right aligned collapsing">Owner</th>
                                            <th v-if="!fileManagerEditorEnabled" class="right aligned collapsing">Size</th>
                                            <th v-if="!fileManagerEditorEnabled" class="right aligned collapsing">Action</th>
                                          </tr></thead>

                                          <tbody v-if="!fileManagerEditorEnabled">
                                            
                                            <tr v-if=" item.filename != '.' &&  item.filename != '.ssh'" v-for="item in fileManagerList">
                                              <td v-if="item.type == 1" colspan="2" class="collapsing">
                                                <a class="file" @click="fileManagerEditFile(fileManagerWD + '/' + item.filename);"><i class="file icon"></i><span>{{item.filename}}</span></a>
                                              </td>
                                              <td v-if="item.type == 2" colspan="2" class="collapsing">
                                                <a class="folder" @click="fileManagerListPath(fileManagerWD, item.filename)"><i class="folder icon"></i><span>{{item.filename}}</span></a>
                                              </td>
                                              <td class="right aligned collapsing">{{item.uid == fileManagerOwnerUid ? LoggedInUser.userUserName : "-"}}</td>
                                              <td v-if="item.type == 2" class="right aligned collapsing">Folder</td>
                                              <td v-if="item.type == 1" class="right aligned collapsing">{{formatBytes(item.size)}}</td>
                                              <td class="right aligned collapsing">
                                                <i v-if="item.type == 1" @click="fileManagerEditorDownload(fileManagerWD + '/' + item.filename, item.filename)" data-content="Download" data-variation="inverted mini" class="download icon"></i>
                                                <i v-if="item.filename != '..'" @click="fileManagerEditorDelete(item.filename)" data-content="Delete" data-variation="inverted mini" class="trash alternate icon"></i>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                        <div style="display:none;" v-bind:class="{ expanded: isterminalExpanded }" id="fileManagerEditor"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="sixteen wide column animated fadeInUp" v-if="isAdminEnabled">
                        <div class="ui segment colored trends resizable" id="geo_map_container">
                            <div class="ui top left attached label">Geo Location</div>
                            <div class="ui top right attached label control"><i @click="viewGeoMap" class="refresh icon"></i></div>

                            <div id="geo_map"></div>

                        </div>
                    </div>
                    <div class="sixteen wide column animated fadeInUp" v-if="isAdminEnabled">
                        <div class="ui segment colored trends">
                            <!-- <p class="title animated fadeIn">Users</p>-->
                            <div class="ui top left attached label">Users</div>
                            <trend :data="userstrend" :gradient="['#D9C285', '#E63946']" auto-draw smooth>
                            </trend>
                        </div>
                    </div>
                    <div class="sixteen wide column animated fadeInUp" v-if="isAdminEnabled">
                        <div class="ui segment colored trends">
                            <div class="ui top left attached label">Broadcast</div>

                            <form class="ui form">
                                <div class="ui stackable grid">
                                    <div class="thirteen wide column fluid input">
                                        <input v-model="TGMessage" type="text" placeholder="Broadcast message to admins..">
                                    </div>

                                    <div class="three wide column">
                                        <a @click="sendTGMessage" class="ui item fluid button hoverable">
                                            <i class="send outline icon"></i> Send
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                
                
                
                
                <div id="serverSelectionModal" class="ui basic modal">
          <div class="ui icon header">
            <i class="server icon"></i>
            Server Selection
          </div>
          <div class="content">
            <p>Select required server to perform File Manager / Terminal actions on:</p>
            
          </div>
          <div class="actions">
            <div class="ui red basic cancel inverted button">
              <i class="remove icon"></i>
              Cancel
            </div>
            <div class="ui green ok inverted button">
              <i class="checkmark icon"></i>
              Save
            </div>
          </div>
        </div>
        
        
        
            </div>
            
            
            
            
            
            
        </script>
    <script type="x-template" id="users">
        <div>
            <div v-if="!isInUserViewMode" id="usersContent">
                <h1 class="inline animated fadeIn">{{showGroups ? "GROUPS" : "USERS"}}</h1>
                <button v-on:click="toggleShowGroups" class="mini ui button right floated">
                    {{!showGroups ? "View Groups" : "View Users"}}
                </button>
                <button v-on:click="syncUsers" v-bind:class="syncingUsers.syncing ? 'loading' : syncingUsers.synced ? 'disabled' : ''"  class="mini ui yellow basic button right floated">
                    {{ syncingUsers.synced ? 'Synced' : 'Sync Users' }}
                </button>
                <div v-if="!showGroups">
                    <div class=" ui basic segment animated fadeInUp">
                        <div class="ui large transparent left icon input">
                            <i class="search icon"></i>
                            <input type="text" v-model="searchUsersInput" placeholder="Search...">
                        </div>
                        <a v-on:click="listUsers" data-tooltip="Refresh" data-inverted="" class="mini basic ui icon right floated button">
                            <i class="refresh icon"></i>
                        </a>
                    </div>
                    <table class="ui celled striped inverted table users animated fadeInUp shadow">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    User
                                </th>
                                <th colspan="1">
                                    Online
                                </th>
                                <th colspan="1">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in filteredUserData">
                                <td class="collapsing">

                                    {{user.id}}. {{user.username}}
                                </td>
                                <td>{{user.name}}</td>
                                <td>{{user.email}}</td>
                                <td>{{ sinceLastOnline(user.lastOnline)}}</td>
                                <td class="right aligned collapsing">
                                    <div class="ui icon buttons">
                                        <button v-on:click="viewUser(user)" class="ui inverted red basic button" data-tooltip="View" data-position="top left"><i class="ellipsis horizontal icon"></i></button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="" v-if="showGroups">
                    <form  v-on:submit.prevent="" class="ui form segment colored trends animated fadeInUp">
                        <a v-if="!groupCreationEnabled" v-on:click="groupCreationEnabled = true" class="fluid ui mini button hoverable"><i class="add icon"></i> Group Creation</a>
                        <div v-if="groupCreationEnabled" class="ui fluid mini icon input">
                            <a v-on:click="groupCreationEnabled = false" class="ui button mini hoverable"><i class="remove icon"></i></a>
                            <input @keyup.enter="createGroup" v-model="createGroupName" type="text" placeholder="Group name">
                            <i class="add icon"></i>
                        </div>
                    </form>
                    <table class="ui celled striped inverted table users animated fadeInUp shadow segment colored trends">
                        <thead>
                            <tr>
                                <th colspan="1">
                                    ID
                                </th>
                                <th colspan="1">
                                    Name
                                </th>
                                <th colspan="1">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="group in groupsList">
                                <td class="collapsing">
                                    {{group.id}}
                                </td>
                                <td>
                                    {{group.name}}
                                </td>
                                <td class="right aligned collapsing">
                                    <i v-if="group.id > 2" v-on:click="deleteGroup(group)" style="cursor:pointer;" class="trash icon"></i>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div v-if="isInUserViewMode" id="viewUser">
                <h1 class="inline animated fadeIn">{{selectedUser.user.id}}. {{ selectedUser.user.name }} </h1>
                <a v-on:click="listUsers" data-tooltip="Back to users" data-inverted="" class="mini basic ui icon right floated button animated fadeIn"><i class="arrow left icon"></i></a>
                <a v-on:click="viewUser(selectedUser.user)" data-tooltip="Refresh" data-inverted="" class="mini basic ui icon right floated button animated fadeIn"><i class="sync icon"></i></a>
                <div id="userDetails" class=" ui basic segment animated fadeInUp">
                 <h4 class="ui horizontal divider header">
                			<span class="ui item"><i class="info icon"></i> <span>Account Information</span></span>

                		</h4>
                    <div class="item">
                		<div class="content">
                			<div class="header">
                				<i class="heartbeat icon"></i> Active:

                				<div class="right floated content">

                					<i v-bind:class="isUserOnline ? 'green' : 'red'" class="circle  icon ni"></i> {{userSinceLastOnline}}

                				</div>
                			</div>
                		</div>
                	</div>
                	<div class="ui divider"></div>
                	<div class="item">
                		<div class="content">
                			<div class="header">
                				<i class="server icon"></i> System Account:

                				<div class="right floated content">
                                    
                					<div v-if="selectedUser.user.systemUserExists"><i class="circle green icon ni"></i> <span style="cursor:pointer" @click="removeSystemUser">[<i class="remove user icon"></i> Remove System User]</span></div>
                					<div v-if="!selectedUser.user.systemUserExists"><i class="circle red icon ni"></i> <span style="cursor:pointer" @click="createSystemUser">[<i class="add user icon"></i> Create System User]</span></div>
                					

                				</div>
                			</div>
                		</div>
                	</div>
                	<div class="ui divider"></div>
                	<div class="item">
                		<div class="content">
                			<div class="header">
                				<i class="hashtag icon"></i> ID:

                				<div class="right floated content">
                              {{selectedUser.user.id}}
                            </div>
                			</div>
                		</div>
                	</div>
                	<div class="ui divider"></div>
                	<div class="item">
                		<div class="content">
                			<div class="header">
                				<i class="user icon"></i> Name:

                				<div class="right floated content">
                              {{selectedUser.user.name}}
                            </div>
                			</div>
                		</div>
                	</div>
                	<div class="ui divider"></div>
                	<div class="item">
                		<div class="content">
                			<div class="header">
                				<i class="id badge icon"></i> Role:

                				<div class="right floated content">
                              {{selectedUser.user.isAdmin == "1" ? 'Adminstrator' : 'User'}}
                            </div>
                			</div>
                		</div>
                	</div>
                	<div class="ui divider"></div>
                	<div class="item">
                		<div class="content">
                			<div class="header">
                				<i class="id badge icon"></i> Group:

                				<div class="right floated content">
                              {{selectedUserGroup}}
                            </div>
                			</div>
                		</div>
                	</div>
                	<div class="ui divider"></div>
                	<div class="item">
                		<div class="content">
                			<div class="header">
                				<i class="mail icon"></i> Email:

                				<div class="right floated content">
                              {{selectedUser.user.email}}
                           </div>
                			</div>
                		</div>
                	</div>
                	<div class="ui divider"></div>
                	<div class="item">
                		<div class="content">
                			<div class="header">
                				<i class="address card icon"></i> Username:

                				<div class="right floated content">
                              {{selectedUser.user.username}}
                            </div>
                			</div>
                		</div>
                	</div>
                	<div class="ui divider"></div>
                	<div class="item">
                		<div class="content">
                			<div class="header">
                				<i class="calendar icon"></i> Joining Date:

                				<div class="right floated content">
                            {{joiningDateGMT}}
                            </div>
                			</div>
                		</div>
                	</div>
                        <h4 class="ui horizontal divider header">
                			<span class="ui item"><i class="hand paper icon"></i> <span>Account Control</span></span>

                		</h4>
                		<div>
                		     <div class="ui colored trends segment item">
                                <div class="content">
                            			<div class="header">
                            				<i v-bind:class="selectedUser.user.isactive == '0' ? 'red' : 'green'" class="circle icon ni"></i> Account:  {{selectedUser.user.isactive == '0' ? 'Awaiting Activation.' : 'Active.'}}
                            				<div v-bind:data-tooltip="selectedUser.user.isactive == '0' ? 'Activate Account' : 'Disable Account'" v-on:click="toggleUserActive" data-inverted="" data-position="top center"  class="right floated content pointer">
                            					<i v-bind:class="selectedUser.user.isactive == '0' ? 'lock open' : 'lock'" class=" icon"></i>
                            				</div>
                            			</div>
                            		</div>
                            </div>
                            <div class="ui colored trends segment item">
                                <div class="content">
                            			<div class="header">
                            				<i class="circle red icon ni"></i> Delete Account
                            				<div data-tooltip="Permenantly Delete Account" data-inverted="" v-on:click="deleteUser" data-position="top center"  class="right floated content pointer">
                            					<i class="trash icon"></i>
                            				</div>
                            			</div>
                            		</div>
                            </div>
                		</div>
                		<h4 class="ui horizontal divider header">
                			<a v-on:click="isInUserView2FAMode = !isInUserView2FAMode" class="ui item tiny button hoverable"><i v-bind:class="isInUserView2FAMode ? 'remove' : 'lock'" class=" icon"></i> <span>User's 2FA Token</span></a>

                		</h4>
                		<transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                		    <div class="" v-if="isInUserView2FAMode" >
                		        <div class="ui colored trends segment item">
                                    <div class="content">
                            			<div class="header">
                            				> {{selectedUser.user.tfaSecret}}
                            				<div data-tooltip="Should not be shared." data-inverted="" data-position="top center"  class="right floated content pointer">
                            					<i class="info icon"></i>
                            				</div>
                            			</div>
                            		</div>
                                </div>
                		    </div>
                        </transition>
                        <h4 class="ui horizontal divider header">
                			<a v-on:click="isInUserViewAPIPermissions = !isInUserViewAPIPermissions; apiAccessDropdown()" class="ui item tiny button hoverable"><i v-bind:class="isInUserViewAPIPermissions ? 'remove' : 'lock'" class=" icon"></i> <span>User's API permissions</span></a>

                		</h4>
                		<transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                		    <div class="" v-if="isInUserViewAPIPermissions" >
                		        <div class="ui colored trends segment item">
                                    <div class="content">
                            			<div class="header">
                                            <select multiple="" class="ui dropdown fluid apiAccess">
                                                <option v-for="item in selectedUser.user.api.apiAccessAvailable" v-bind:value="item">{{item}}</option>
                                            </select>
                                            <div data-tooltip="Apply Permissions" data-inverted="" v-on:click="apiAccessUpdate" data-position="top center"  class="right floated content pointer">
                                                <i style="padding: 10px;" class="check icon"></i>
                                            </div>
                            				
                            			</div>
                            		</div>
                                </div>
                		    </div>
                		</transition>
                		<h4 class="ui horizontal divider header">
                			<a v-on:click="isInUserMonitorMode = !isInUserMonitorMode" class="ui item tiny button hoverable"><i v-bind:class="isInUserMonitorMode ? 'remove' : 'spy'" class=" icon"></i> <span>User Monitoring</span></a>

                		</h4>
                		<transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                		<div class="" v-if="isInUserMonitorMode" >
                        <div class="item">
                        	<div class="content">
                        		<div class="ui colored trends segment">
                        			<div class="ui top left attached label">Geo Location</div>
                        		</div>
                        	</div>
                        </div>
                        <div class="ui divider"></div>
                        <div class="item">
                        	<div class="content">
                        		<div class="ui colored trends segment">
                        			<div v-if="browserRecordsEnabled" class="ui top left attached label">Browser Records</div>
                        			<div class="ui top right attached label control">
                        				<i @click="getBrowserRecords" v-if="browserRecordsEnabled" class="refresh icon"></i>
                        				<i class="close icon" @click="closeBrowserRecords" v-if="browserRecordsEnabled"></i>
                        			</div>
                        			<div v-if="!browserRecordsEnabled" class="ui center aligned overlay segment"><button @click="getBrowserRecords" class="ui button"><i class="desktop icon"></i> Browser Records</button></div>
                        			<div class="user trend">
                        				<trend v-if="browserRecordsEnabled" :data="browserFrequencyTrend"
                                    :gradient="['#D9C285', '#E63946']"
                                    auto-draw
                                    smooth></trend>
                        			</div>
                        			<table class="ui very basic celled inverted table">
                        				<thead v-if="browserRecordsEnabled" class="animated fadeInUp">
                        					<th>Login</th>
                        					<th>Frequency</th>
                        					<th>Details</th>
                        				</tr>
                        			</thead>
                        			<tbody v-if="browserRecordsEnabled" class="animated fadeInUp">
                        				<tr v-for="item in browserRecords">
                        					<td>
                        						<h4 class="ui header">
                        							<div class="content">
                                    {{item.browserIP}}



                        								<div class="sub header">{{item.timestamp}}
                                  </div>
                        							</div>
                        						</h4>
                        					</td>
                        					<td>{{item.loginCount}}</td>
                        					<td>
                        						<div class="ui list">
                        							<div class="item">Fingerprint: {{item.fingerprint}}</div>
                        							<div class="item">Cookies: {{item.browserCookies}}</div>
                        							<div class="item">Language: {{item.browserLanguage}}</div>
                        							<div class="item">Browser: {{item.browserName}}</div>
                        							<div class="item">Browser Version: {{item.browserVersion}}</div>
                        							<div class="item">Platform: {{item.browserPlatform}}</div>
                        							<div class="item">History Length: {{item.historyLength}}</div>
                        							<div class="item">Java: {{item.javaEnabled}}</div>
                        							<div class="item">Referrer: {{item.referrer}}</div>
                        							<div class="item">ScreenSize: {{item.screenSize}}</div>
                        						</div>
                        					</td>
                        				</tr>
                        			</tbody>
                        		</table>
                        	</div>
                        </div>
                        </div>
                        <div class="ui divider"></div>
                        <div class="item">
                        	<div class="content">
                        		<div class="ui colored trends segment">
                        			<div v-if="isInUserViewActivityMode" class="ui top left attached label">Activity</div>
                        			<div class="ui top right attached label control">
                        				<i @click="getActivity" v-if="isInUserViewActivityMode" class="refresh icon"></i>
                        				<i class="close icon" @click="closeActivity" v-if="isInUserViewActivityMode"></i>
                        			</div>
                        			<div v-if="!isInUserViewActivityMode" class="ui center aligned overlay segment"><button @click="getActivity" class="ui button"><i class="print icon"></i> Recent Activity</button></div>
                        			<table class="ui very basic celled inverted table">
                        				<thead v-if="isInUserViewActivityMode" class="animated fadeInUp">
                        					<th>Time</th>
                        					<th>Type</th>
                        					<th>Comment</th>
                        				</tr>
                        			</thead>
                        			<tbody v-if="isInUserViewActivityMode" class="animated fadeInUp">
                        				<tr v-for="item in usersActivity">
                        					<td>{{item.timestamp}}</td>
                        					<td>{{item.type}}</td>
                        					<td>{{item.comment}}</td>
                        				</tr>
                        			</tbody>
                        		</table>
                        	</div>
                        </div>
                        </div>
                        <div class="ui divider"></div>
                        <div class="item">
                        <div class="content">
                        	<div class="ui colored trends segment">
                                <div v-if="loginRecordsEnabled" class="ui top left attached label">Login Attempts</div>
                                    <table v-if="loginRecordsEnabled" class="ui inverted table">
                                        <thead>
                                        <tr>
                                            <th>D/H</th>
                                            <th>0</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th>
                                        </tr></thead>
                                        <tbody>
                                            <tr v-for="(day,indexDay) in loginRecordsTimestamp.daysHrs">
                                                <td>{{indexDay}}</td><td v-for="hour in day">{{hour}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                        		<div class="ui top right attached label control">
                        			<i @click="getLoginRecords" v-if="loginRecordsEnabled" class="refresh icon"></i>
                        			<i class="close icon" @click="closeLoginRecords" v-if="loginRecordsEnabled"></i>
                        		</div>
                        		<div v-if="!loginRecordsEnabled" class="ui center aligned overlay segment"><button @click="getLoginRecords" class="ui button"><i class="key icon"></i> Recent Logins</button></div>
                        		<div class="user trend">
                        			<trend v-if="loginRecordsEnabled" :data="failedLoginsTrend"
                                  :gradient="['#D9C285', '#E63946']"
                                  auto-draw
                                  smooth></trend>
                        		</div>
                        		<table class="ui very basic celled inverted table">
                        			<thead v-if="loginRecordsEnabled" class="animated fadeInUp">
                        				<th>Login Attempt</th>
                        				<th>Details</th>
                        			</tr>
                        		</thead>
                        		<tbody v-if="loginRecordsEnabled" class="animated fadeInUp">
                        			<tr v-for="item in loginRecords.slice(0,loginRecordsN)">
                        				<td>
                        					<h4 class="ui header">
                        						<div class="content">
                                  {{item.ip}}



                        							<div class="sub header">{{item.timestamp}}
                                </div>
                        						</div>
                        					</h4>
                        				</td>
                        				<td>
                        					<div class="ui list">
                        						<div class="item">Fingerprint: {{item.fingerprint}}</div>
                        						<div class="item">Fails: {{item.fails}}</div>
                        					</div>
                        				</td>
                        			</tr>
                        		</tbody>
                        	</table>
                        	<a @click="toggleLoginRecordsShowMore" v-if="loginRecordsShowMore && loginRecordsEnabled" class="ui button fluid">Show More</a>
                        </div>
                        </div>
                        </div>
                        </div>
                        </transition>
                </div>
            </div>

        </div>
        </script>
    <script type="x-template" id="settings">
        <div>
                <h1 class="inline animated fadeIn">SETTINGS</h1>
                <div class="ui secondary menu settingsConfig animated fadeInUp">
                    <a class="item active" data-tab="site"><i class="columns icon"></i> Site</a>
                    <a class="item" data-tab="security"><i class="bug outline icon"></i> Security</a>
                    <a class="item" data-tab="smtp"><i class="mail outline icon"></i> SMTP</a>
                    <a class="item" data-tab="cookie"><i class="disk outline icon"></i> Cookie</a>
                    <a class="item" data-tab="logs"><i class="file alternate outline icon"></i> Logs</a>
                    <a class="item" data-tab="servers"><i class="file alternate outline icon"></i> Servers</a>
                    <a class="item right aligned" @click="saveConfig"><i class="save icon"></i> Save</a>

                </div>
                <div data-tab="site" class="ui inverted active tab segment animated fadeInUp">
                    <div class="ui top left attached label">Site Configuration</div>
                    <form class="ui form">
                        <div class="fields">
                            <div class="eight wide field">
                                <label>Site Name</label>
                                <input type="text" v-model="config.site_name">
                            </div>
                            <div class="eight wide field">
                                <label>Site Email</label>
                                <input type="email" v-bind:value="config.site_email">
                            </div>
                        </div>
                        <div class="fields">
                            <div class="eight wide field">
                                <label>Site URL</label>
                                <input type="text" v-bind:value="config.site_url">
                            </div>
                            <div class="eight wide field">
                                <label>Site Timezone</label>
                                <input type="text" v-bind:value="config.site_timezone">
                            </div>
                        </div>
                        <div class="fields">
                            <div class="eight wide field">
                                <label>Site Activation Page</label>
                                <input type="text" v-bind:value="config.site_activation_page">
                            </div>
                            <div class="eight wide field">
                                <label>Site Password Reset Page</label>
                                <input type="text" v-bind:value="config.site_password_reset_page">
                            </div>
                        </div>
                    </form>
                </div>
                <div data-tab="security" class="ui inverted tab segment animated fadeInUp">
                    <div class="ui top left attached label">Cookie Configuration</div>
                    <form class="ui form">
                        <div class="fields">
                            <div class="eight wide field">
                                <label>Attach Mitigation Time</label>
                                <input type="text" v-bind:value="config.attack_mitigation_time">
                            </div>
                            <div class="eight wide field">
                                <label>Attempts Before Ban</label>
                                <input type="text" v-bind:value="config.attempts_before_ban">
                            </div>
                        </div>
                        <div class="fields">
                            <div class="eight wide field">
                                <label>Attempts Before Verify</label>
                                <input type="text" v-bind:value="config.attempts_before_verify">
                            </div>
                            <div class="eight wide field">
                                <label>Password Minimum Score (1-4)</label>
                                <input type="text" v-bind:value="config.password_min_score">
                            </div>
                        </div>
                    </form>
                </div>
                <div data-tab="smtp" class="ui inverted tab segment animated fadeInUp">
                    <div class="ui top left attached label">SMTP Configuration</div>
                    <form class="ui form">
                        <div class="fields">
                            <div class="eight wide field">
                                <label>SMTP Enable</label>
                                <div class="ui selection dropdown">
                                    <input v-bind:value="config.smtp" type="hidden"> <i class="dropdown icon"></i>
                                    <div class="default text">SMTP Status</div>
                                    <div class="menu">
                                        <div class="item" data-value="1">Enabled</div>
                                        <div class="item" data-value="0">Disabled</div>
                                    </div>
                                </div>
                            </div>
                            <div class="eight wide field">
                                <label>SMTP Authorization</label>
                                <div class="ui selection dropdown">
                                    <input v-bind:value="config.smtp_auth" type="hidden"> <i class="dropdown icon"></i>
                                    <div class="default text">SMTP Status</div>
                                    <div class="menu">
                                        <div class="item" data-value="1">Enabled</div>
                                        <div class="item" data-value="0">Disabled</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="sixteen wide field">
                                <label>SMTP Host</label>
                                <input type="text" v-bind:value="config.smtp_host">
                            </div>
                        </div>
                        <div class="fields">
                            <div class="eight wide field">
                                <label>SMTP Username</label>
                                <input type="text" v-bind:value="config.smtp_username">
                            </div>
                            <div class="eight wide field">
                                <label>SMTP Password</label>
                                <input type="password" v-bind:value="config.smtp_password">
                            </div>
                        </div>
                        <div class="fields">
                            <div class="eight wide field">
                                <label>SMTP Port</label>
                                <input type="text" v-bind:value="config.smtp_port">
                            </div>
                            <div class="eight wide field">
                                <label>SMTP Security</label>
                                <input type="text" v-bind:value="config.smtp_security" disabled>
                            </div>
                        </div>
                    </form>
                </div>
                <div data-tab="cookie" class="ui inverted tab segment animated fadeInUp">
                    <div class="ui top left attached label">Cookie Configuration</div>
                    <form class="ui form">
                        <div class="fields">
                            <div class="eight wide field">
                                <label>Cookie HTTP</label>
                                <div class="ui selection dropdown">
                                    <input v-bind:value="config.cookie_http" type="hidden"> <i class="dropdown icon"></i>
                                    <div class="default text">SMTP Status</div>
                                    <div class="menu">
                                        <div class="item" data-value="1">Enabled</div>
                                        <div class="item" data-value="0">Disabled</div>
                                    </div>
                                </div>
                            </div>
                            <div class="eight wide field">
                                <label>Cookie Secure</label>
                                <div class="ui selection dropdown">
                                    <input v-bind:value="config.cookie_secure" type="hidden"> <i class="dropdown icon"></i>
                                    <div class="default text">SMTP Status</div>
                                    <div class="menu">
                                        <div class="item" data-value="1">Enabled</div>
                                        <div class="item" data-value="0">Disabled</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="eight wide field">
                                <label>Cookie Session Time</label>
                                <input type="text" v-bind:value="config.cookie_forget">
                            </div>
                            <div class="eight wide field">
                                <label>Cookie Remember Time</label>
                                <input type="text" v-bind:value="config.cookie_remember">
                            </div>
                        </div>
                    </form>
                </div>
                <div data-tab="logs" class="ui inverted tab segment animated fadeInUp">
                    <div class="ui top left attached label">Logs</div>
                    <form v-for="log in logs" class="ui form">
                        <div class="fields">
                            <div class="six wide field">
                                <label>Name</label>
                                <input type="text" v-bind:value="log.name">
                            </div>
                            <div class="ten wide field">
                                <label>Location</label>
                                <input type="text" v-bind:value="log.location">
                            </div>
                        </div>
                    </form>
                </div>
                <div data-tab="servers" class="ui inverted tab segment animated fadeInUp">
                    <div class="ui top left attached label">Servers</div>
                    <form v-for="server in servers" class="ui form">
                        <div class="fields">
                            <div class="two wide field">
                                <label>Enabled</label>
                                <button class="ui icon button"><i class="check square icon"></i></button>
                            </div>
                            <div class="six wide field">
                                <label>Name</label>
                                <input type="text" v-bind:value="server.name">
                            </div>
                            <div class="six wide field">
                                <label>IP</label>
                                <input type="text" v-bind:value="server.ip">
                            </div>
                            <div class="two wide field">
                                <label>Port</label>
                                <input type="text" v-bind:value="server.port">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </script>
    <script type="x-template" id="about">
        <div>
                <h1 class="inline animated fadeIn">ABOUT</h1>
            </div>
        </script>
    <script type="x-template" id="ai">
        <div class="ai">
                <h1 class="inline animated fadeIn">AI</h1>

                <div class="ui toggle checkbox boxy margin-top-10 right floated inline checked animated fadeIn">

                    <input type="checkbox" checked tabindex="0">
                </div>


                <div class="ui segment colored trends animated fadeInUp padding-top-40">
                    <div class="ui top left attached label">AI Simulator</div>
                    <div class="ui top right attached label control"><i @click="CalculateProb" data-content="Simulate" data-position="left center" class="square icon"></i></div>

                    <form class="ui form">
                        <div class="five fields">
                            <div class="field">
                                <input type="text" v-model="Calc_fp" placeholder="Fingerprint %">
                            </div>
                            <div class="field">
                                <input type="text" v-model="Calc_s" placeholder="2nd Session?">
                            </div>
                            <div class="field">
                                <input type="text" v-model="Calc_f" placeholder="Fails %">
                            </div>
                            <div class="field">
                                <input type="text" v-model="Calc_l" placeholder="Location %">
                            </div>
                            <div class="field">
                                <input type="text" v-model="Calc_allow" placeholder="Allow %" disabled>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </script>

    <script type="x-template" id="visualize">
        <div class="visualize">
                <h1 class="inline animated fadeIn">Visualize</h1>


                <div class="ui segment colored trends animated fadeInUp padding-top-40">
                    <div class="ui top left attached label"> Time of use</div>
                    <table class="ui inverted table">
                        <thead>
                        <tr>
                            <th>D/H</th>
                            <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>21</th><th>22</th><th>23</th><th>24</th>
                        </tr></thead>
                        <tbody>
                            <tr>
                            
                            </tr>
                            <tr>
                            
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
        </script>

    <script type="x-template" id="logs">
        <div class="logs">
                <h1 class="inline animated fadeIn">LOGS</h1>

                <div class="ui secondary menu animated fadeInUp">
                    <a class="item" data-tab="Requests"> Requests</a>
                    

                </div>
                <div data-tab="Requests" class="ui inverted tab animated fadeInUp">
                    <h2 class="inline animated fadeIn">Caddy Requests Logs</h2>
                    <div class="ui selection dropdown compact inverted right floated">
                                    <input type="hidden"> <i class="dropdown icon"></i>
                                    <div class="default text"># of requests</div>
                                    <div class="menu">
                                        <div v-on:click="logs.numberOfRequests = 10;logRequests()" class="item" data-value="10">10</div>
                                        <div v-on:click="logs.numberOfRequests = 50;logRequests()" class="item" data-value="50">50</div>
                                        <div v-on:click="logs.numberOfRequests = 100;logRequests()" class="item" data-value="100">100</div>
                                        <div v-on:click="logs.numberOfRequests = 200;logRequests()" class="item" data-value="200">200</div>
                                    </div>
                                </div>
                   
                    <div class="ui inverted segment">
                      <div class="ui inverted accordion">
                      <div v-for="r in logs.requests">
                        <div class="title">
                          <i class="dropdown icon"></i>
                            <div v-bind:class="r[0] == 'GET' ? 'yellow' : 'blue'" class="ui label">
                              <i v-bind:class="r[0] == 'GET' ? 'down' : 'up'" class="arrow icon"></i> {{r[0]}}
                            </div>
                            <span> {{r[2] + "://" + r[3] + r[4] + r[5] + r[6]}}</span>
                            <div class="ui label right floated">
                              <div class="detail">{{r[8]}}</div>
                            </div>
                            <div v-bind:class="r[12] == '200' ? 'green' : 'red'" class="ui label right floated">
                              <div class="detail">{{r[12]}}</div>
                            </div>
                        </div>
                        <div class="content">
                          <p class="transition hidden">
                            <div class="ui inverted list">
                              <a class="item">
                                <i class="help icon"></i>
                                <div class="content">
                                  <div class="header">MITM</div>
                                  <div class="description">{{r[14]}}</div>
                                </div>
                              </a>
                              <a class="item">
                                <i class="help icon"></i>
                                <div class="content">
                                  <div class="header">Time</div>
                                  <div class="description">{{unixToDate(r[7])}}</div>
                                </div>
                              </a>
                              <a class="item">
                                <i class="help icon"></i>
                                <div class="content">
                                  <div class="header">Referer</div>
                                  <div class="description">{{r[15]}}</div>
                                </div>
                              </a>
                              <a class="item">
                                <i class="help icon"></i>
                                <div class="content">
                                  <div class="header">Scheme</div>
                                  <div class="description">{{r[1]}}</div>
                                </div>
                              </a>
                              <a class="item">
                                <i class="help icon"></i>
                                <div class="content">
                                  <div class="header">Size</div>
                                  <div class="description">{{r[11]}}</div>
                                </div>
                              </a>
                              <a class="item">
                                <i class="help icon"></i>
                                <div class="content">
                                  <div class="header">User Agent</div>
                                  <div class="description">{{r[10]}}</div>
                                </div>
                              </a>
                            </div>
                          </p>
                        </div>
                        <hr>
                        </div>
                      </div>
                    </div>
                </div>
                

            </div>
        </script>


    <script type="x-template" id="nope">
        <div class="jumbotron">
                <h3>Nope</h3>
                <p>Remember the route with no title, you can navigate to it!</p>
            </div>
        </script>

    <!-----------Modals Start Here-------------->

    <div class="ui tiny confirmation modal">
        <div class="colored image content">

            <div class="confirmationText">
            </div>
        </div>
        <div class="colored trends actions">
            <div class="ui red basic cancel inverted button">
                <i class="remove icon"></i> No
            </div>
            <div class="ui green ok inverted button">
                <i class="checkmark icon"></i> Yes
            </div>
        </div>
    </div>






    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script src="./assets/js/idicon.js"></script>
    <script src="./assets/js/scripts.js"></script>
    <script src="./assets/js/ivue.js"></script>
    <script src="./assets/js/ivue-router.js"></script>
    <script src="./assets/js/loading.js"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script type="text/javascript" src="./assets/js/antiTrackingBlocker.js"></script>
    <script>
        Vue.use(Toasted);
    </script>
</body>

</html>