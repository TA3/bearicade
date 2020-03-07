<?php
require '../vendor/autoload.php';
require '../loginConfig.php';
if (isset($_COOKIE['authID'])) {
  $auth->logout($_COOKIE['authID']);
  unset($_COOKIE['authID']);
}
$secret = $tfa->createSecret();
$qrImage = $tfa->getQRCodeImageAsDataUri('HPC User', $secret);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/semantic.css">
    <link rel="stylesheet" href="../../assets/css/animate.css">
    <link rel="stylesheet/less" href="../../assets/css/styles.less">
    <style type="text/less">

    </style>
    <script src="../../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../../assets/js/semantic.js"></script>
    <script src="../../assets/js/less.min.js"></script>
    <script src="../../assets/js/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.0.3/fingerprint2.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script type="text/javascript" src="../../assets/js/regex.js"></script>
    <script type="text/javascript" src="../../assets/js/zxcvbn.js"></script>
    <title>Dashboard: Register</title>
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
    <div id="register">

        <register></register>
    </div>


    <script type="x-template" id="registerTemplate">
            <div class="ui container animated fadeInDown">
                    <h2 class="ui header">
                            <i class="html5 icon"></i>
                            <div class="content">
                              <div class="sk">Dashboard</div>
                              <div class="sub header">Register</div>
                            </div>
                          </h2>
                <div class="ui center aligned colored trends segment">
                        <form v-on:submit.prevent class="ui form">
                            <div class="fields animated fadeIn" v-show="getStep === 1">
                                <div class="sixteen wide field">
                                  <label class="left floated">Name</label>
                                  <input type="text" v-model='name' placeholder="Full Name" autofocus>
                                </div>
                            </div>
                            <div class="fields animated fadeIn" v-show="getStep === 2">
                                <div class="sixteen wide field">
                                  <label class="left floated">Username</label>
                                  <input type="text" v-model='username' placeholder="username" autofocus>
                                </div>
                            </div>
                            <div class="fields animated fadeIn" v-show="getStep === 3">
                                <div class="sixteen wide field">
                                  <label class="left floated">Email</label>
                                  <input type="email" v-model='email' placeholder="e.g (u******@unimail.hud.ac.uk)" autofocus>
                                </div>
                            </div>
                            <div class="fields animated fadeIn" v-show="getStep === 4">
                                <div class="eight wide field">
                                  <label class="left floated">Password</label>
                                  <input type="password" v-model='password' placeholder="********">
                                </div>
                               <div class="eight wide field">
                                  <label class="left floated">Password Conformation</label>
                                  <input type="password" v-model='cpassword' placeholder="********">
                                </div>
                            </div>
                            <div class="fields animated fadeIn" v-show="getStep === 5">
                                <div class="four wide field">

                                  <img class="ui medium rounded image QRImage" src="<?php echo $qrImage; ?>"></img>
                                </div>
                               <div class="twelve wide field">
                                  <label class="left floated">Secret</label>
                                  <input type="text" v-model='secret' placeholder="<?php echo $secret; ?>" disabled>
                                  <label class="left floated">Code</label>
                                  <div class="ui corner labeled input">
                                  <input type="text" v-model='tfaCode' maxlength="6" minlength="6" placeholder="" focused>
                                    <div @click="showHelp" class="ui corner label">
                                      <i class="question black icon"></i>
                                    </div>
                                  </div>

                                </div>
                            </div>
                            <div class="fields animated fadeIn" v-if="errors.length">
                                  <div class="sixteen wide field">
                                    <label class="left floated">Error:&nbsp;</label>
                                    <p class="left floated" v-for="error in errors"> {{ error }}</p>
                                  </div>
                               </div>

                              </form>
                            </div>
                            <div>
                            <form v-on:submit.prevent class="ui form right floated">
                            <transition name='slide-fade'> <a v-if="submitEnabled" @click="formRegister" data-sitekey="6LeJPWkUAAAAAA7-SeJgjUWXl_CO9mNWdn9E0UCi" data-callback="YourOnSubmitFn" class="ui button g-recaptcha">Register</a></transition>
                           <a class="ui inverted black right floated button animated fadeIn small" v-if="getStep < 5" @click="nextStep">Next</a>
                           <a class="ui inverted black right floated button animated fadeIn small" v-if="getStep > 1" @click="previousStep">Previous</a>
                            </form>
                            <form v-on:submit.prevent class="ui form left floated">
                              <a class="ui inverted gold button animated fadeIn small" v-if="allowLogin" href="../">Login</a>
                            </form>
                        </div>
                </div>
        </script>

    <script src="https://unpkg.com/vue-toasted"></script>

<script>
    Vue.use(Toasted);
    var TFASecret = "<?php echo $secret ?>";
</script>
    <script src="https://cdn.rawgit.com/stewartlord/identicon.js/master/identicon.js"></script>
    <script src="../../assets/js/sha1.js"></script>
    <script src="../../assets/js/functions.js"></script>
    <script src="../../assets/js/ivueRegister.js"></script>
    <script src="../../assets/js/loading.js"></script>
    <script type="text/javascript" src="../../assets/js/antiTrackingBlocker.js"></script>


</body>

</html>