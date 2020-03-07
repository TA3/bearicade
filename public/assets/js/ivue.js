Vue.component('confirm', {
  props: ['text'],
  functional: true,
  render(h, context) {
    // check slots
    if (context.children.length !== 1) {
      console.error('must have exactly one child element!')
      return null
    }
    const el = context.children[0]

    // add listener to slot vnode if specified
    const {
      confirm,
      cancel
    } = context.listeners
    if (confirm) {
      // create a listener callback to apply to the button
      const text = context.props.text || 'Really do this?'
      const wrappedListener = () => {
        const res = confirmation(text);
        var refreshId = setInterval(function () {
          if (approvedConfirmation != undefined) {
            clearInterval(refreshId);
            if (approvedConfirmation) {
              confirm()
            }
            return approvedConfirmation;
          }
        }, 100);
      }

      const data = (el.data || {})
      const on = (data.on || (data.on = {}))
      on.click = wrappedListener
      el.data = data
    }

    return el
  }
})



var menu = new Vue({
  el: '#menu',
  data: {
    isAdminEnabled: true
  },
  computed: {
    LoggedInUser: function () {
      return root.LoggedInUser
    },
    siteName: function () {
      return root.SiteConfig.config.site_name
    }
  },
  methods: {
    toggleAdminMode: function () {
      if (this.isAdminEnabled) {

        this.isAdminEnabled = false;
        UserConfig.terminalEnabled = false
        UserConfig.terminalExpanded = false
      } else {

        this.isAdminEnabled = true;
        UserConfig.terminalEnabled = false
        UserConfig.terminalExpanded = false
      }
    },
    logout: function () {
      loading(true);
      setTimeout(function () {
        logout()
      }, 1000)
    }

  }
})
var profileSidebar = new Vue({
  el: '#profileSidebar',
  data: {
    lightThemeEnabled: cookieConfig.lightTheme
  },
  created: function () {
    this.lightTheme()

  },
  methods: {
    generateAPIKey: function () {
      let result = root.LoggedInUser.API.domain.match(/^https?:\/\/[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/gm) || [];
      if (result.length) {
        Vue.set(root.LoggedInUser.API, 'key', "Generating...");
        var api_info = $.get("/controller/api.php?a=apiManagement&m=generate&d=" + btoa(root.LoggedInUser.API.domain), function () {

          })
          .done(function (data) {
            if (!data.error) {
              Vue.set(root.LoggedInUser.API, 'key', data.key);
              Vue.toasted.show("Keep your API key safe, if the key stops working, regenerate.", {
                position: "top-left",
                singleton: true,
                duration: 30000,
              });
            } else {
              Vue.set(root.LoggedInUser.API, 'key', "Failed to generate");
            }
          })
          .fail(function () {
            Vue.toasted.show("Error: Request Failed", {
              position: "top-left",
              singleton: true,
              duration: 30000,
            });
          });
      } else {
        Vue.toasted.show("Error: Invalid Domain, include protocol and domain", {
          position: "top-left",
          singleton: true,
          duration: 30000,
        });
      }

    },
    lightTheme: function () {
      if (this.lightThemeEnabled) {
        var css = 'body{filter: invert(85%);}.ni{filter:invert(85%) !important;}.shadow, .ui.segment.colored:hover{-webkit-box-shadow: 0px 10px 30px -12px rgba(255,255,255,0.75) !important; -moz-box-shadow: 0px 10px 30px -12px rgba(255,255,255,0.75) !important; box-shadow: 0px 10px 30px -12px rgba(255,255,255,0.75) !important; }',
          head = document.head || document.getElementsByTagName('head')[0],
          style = document.createElement('style');
        style.id = 'lightThemeElement'
        style.type = 'text/css';
        if (style.styleSheet) {
          style.styleSheet.cssText = css;
        } else {
          style.appendChild(document.createTextNode(css));
        }
        head.appendChild(style);
        cookieConfig.lightTheme = true;
      } else {
        try {
          document.getElementById('lightThemeElement').remove();
        } catch (err) {}
        cookieConfig.lightTheme = false;
      }
      setTimeout(() => {
        setCookieConfig('lightTheme', cookieConfig.lightTheme);
      }, 100);
    }
  },
  computed: {
    LoggedInUser: function () {
      return root.LoggedInUser
    }
  }
})



var selectedUser = {};

Vue.component('user-modal', {
  template: `

<div>



</div>

    `,
  data() {
    return {
      selectedUser
    }
  },

  mounted: function () {
    $('#userModal').modal({
      closable: false,
      observeChanges: true
    }).modal('show');
    $('img').popup();
    $('.menu .item').tab();
  },
  methods: {

  },
  computed: {


  }
});