<?php
date_default_timezone_set('UTC');
require './vendor/autoload.php';
require './loginConfig.php';
if (isset($_COOKIE['authID'])) {
  $auth->logout($_COOKIE['authID']);
  unset($_COOKIE['authID']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/semantic.css">
    <link rel="stylesheet" href="../assets/css/animate.css">
    <link rel="stylesheet/less" href="../assets/css/styles.less">
    <style type="text/less">

    </style>
    <script>
      var timeNow = "<?php echo time(); ?>";
      setInterval(function(){timeNow++;document.getElementById("timeNow").innerText = timeConverter(timeNow)},1000);
    </script>
    <script src="../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../assets/js/semantic.js"></script>
    <script src="../assets/js/less.min.js"></script>
    <script src="../assets/js/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.0.3/fingerprint2.min.js"></script>
    <script src="../assets/js/userbehavior.js"></script>
    <script type="text/javascript" src="../assets/js/sk_loader.js"></script>
    <title>Dashboard: Login</title>
    <style>
    /* Enter and leave animations can use different */
/* durations and timing functions.              */
.slide-fade-enter-active {
  transition: all .3s ease;
}
.slide-fade-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}

    </style>
</head>

<body>
    <div id="login">

        <login></login>
    </div>


    <script type="x-template" id="loginTemplate">
            <div class="ui container animated fadeInDown">
                    <h2 class="ui header">
                            <i class="html5 icon"></i>
                            <div class="content">
                              <div class="sk">Dashboard</div>
                              <div class="sub header">Login</div>
                            </div>
                          </h2>
                <div class="ui center aligned colored trends segment">
                        <form class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                  <label class="left floated">Email</label>
                                  <input type="email" v-model='email' placeholder="e.g (u******@hud.ac.uk)" autofocus>
                                </div>
                            </div>
                            <div class="fields">
                                <div class="ten wide field">
                                  <label class="left floated">Password</label>
                                  <input v-on:keyup.enter="formLogin" type="password" v-model='password' placeholder="********">
                                </div>
                                <div class="six wide field">
                                        <label class="left floated">Authenticator Code </label>
                                        <input v-on:keyup.enter="formLogin" type="text" v-model='authCode' onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="!authCodeEnable" placeholder="6-digit code" maxlength="6">
                                </div>
                            </div>
                              </form>
                            </div>
                            <div>
                            <form class="ui form right floated">
                            <transition name='slide-fade'> <a v-if="submitEnable" @click="formLogin" class="ui button">Login</a></transition>
                            <a class="ui inverted gold button small animated fadeIn" data-content="Please enter a valid email, and fill in the password." >Help</a>
                            <a href="/access/register" class="ui inverted gold button small animated fadeIn">Register</a>
                            </form>
                        </div>
                        
                </div>
        </script>
    <script src="https://cdn.rawgit.com/stewartlord/identicon.js/master/identicon.js"></script>
    <script src="../assets/js/sha1.js"></script>
    <script src="../assets/js/functions.js"></script>
    <script src="../assets/js/loading.js"></script>
    <script src="https://unpkg.com/vue-toasted"></script>

<script>
    Vue.use(Toasted)
</script>
    <script src="../assets/js/ivueLogin.js"></script>
    <script type="text/javascript" src="../assets/js/antiTrackingBlocker.js"></script>
    <span id="timeNow" style="position:fixed; bottom: 10px;left: 10px;"></span>
</body>

</html>