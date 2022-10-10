
    <!-- Library -->
    
    <link rel="stylesheet" href="@base('pages:assets/css/lib/grapes.min.css')" />
    <link rel="stylesheet" href="@base('pages:assets/css/lib/grapesjs-plugin-toolbox.min.css')" />
    
    <script src="@base('pages:assets/js/lib/grapes.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-plugin-toolbox.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-plugin-forms.min.js')"></script>
    <script
      src="@base('pages:assets/js/lib/grapesjs-blocks-basic.js')"
      type="text/javascript"
    ></script>

    <!-- Custom -->
    <link rel="stylesheet" href="@base('pages:assets/css/main.css')" />
<div editor-content riot-view>
<div class="loading" show="{ loading }">
  <cp-preloader class="uk-container-center"></cp-preloader>
</div>

<div id="gjs">
      <div class="main-content">
        <nav class="navbar navbar-light">
          <div class="container-fluid">
            <div class="panel__devices"></div>
            <div class="panel__basic-actions"></div>
          </div>
        </nav>
        <div id="editor"></div>
      </div>

      <template id="page">
        <li class="uk-grid uk-grid-small" rel="{{$page['_id']}}">
          <a class="uk-flex-item-1 uk-text-left uk-text-truncate uk-text-muted" rel="page"></a>
          <div class="tools uk-flex-item-1 uk-text-right uk-animation-fade">
            <a class="uk-icon-cog uk-text-muted" rel="page-settings"></a> 
            <a class="uk-icon-trash uk-text-danger" rel="page-remove"></a> 
          </div>
        </li>
      </template>
      
  </div>

<script type="view/script">

  var $this = this;
  this.user = {{ json_encode($app->module('maya')->getUser()) }};
  this.loading = true;
  this.page = {{ json_encode($page) }};
  this.projectID = this.page._id;
  window.TT = this;
  this.on('mount', ()=> {
    this.loading = true;

    this.editor = grapesjs.init({
        container : '#gjs #editor',
        plugins: [
          "gjs-blocks-basic", 
          "grapesjs-plugin-toolbox", 
          "grapesjs-plugin-forms",
          function(editor) {
            // As sessionStorage is not an asynchronous API,
            // the `async` keyword could be skipped
            editor.Storage.add('maya-store', {
              async load(options = {}) {
                return App.callmodule('pages:getFieldValue', [$this.page.name, "data",{}]).then(function(data) {
                  return data.result || {};
                });
              },

              async store(data, options = {}) {
                App.callmodule('pages:setFieldValue', [$this.page.name, "data",data, {
                  createIfNotExists : true
                }]).then(function(data) {
                  App.callmodule('pages:setFieldValue', [$this.page.name, "html", editor.getHtml(), {
                    createIfNotExists : true
                  }]).then(function(data) {
                    App.callmodule('pages:setFieldValue', [$this.page.name, "css",editor.getCss(), {
                      createIfNotExists : true
                    }]).then(function(data) {
                      App.callmodule('pages:setFieldValue', [$this.page.name, "js",editor.getJs(), {
                        createIfNotExists : true
                      }]).then(function(data) {
                        return data.result || {};
                      })
                    })
                  })
                });
              }
            });
          },
        ],
        showDevices: false,
        fromElement : true,
        pluginsOpts: {
          "gjs-blocks-basic": {},
          "grapesjs-plugin-toolbox" :{}
        },
        storageManager: {
          type: 'maya-store',
          stepsBeforeSave: 1
        }
    });
    this.editor.Panels.removeButton('options','export-template')
    this.editor.Panels.addPanel({
      id: 'devices', buttons: [
          { id: "back", command: function (e) { return document.location= "@route('/pages')"; },className: "editor-back", label: '<i class="fa fa-chevron-left"></i>&nbsp;{{$page["name"]}}&nbsp;&nbsp;&nbsp;&nbsp;', },
          { id: "set-device-desktop", command: function (e) { return e.setDevice("Desktop") }, className: "fa fa-desktop", active: 1 },
          { id: "set-device-tablet", command: function (e) { return e.setDevice("Tablet") }, className: "fa fa-tablet" },
          { id: "set-device-mobile", command: function (e) { return e.setDevice("Mobile portrait") }, className: "fa fa-mobile" },
          { id: "set-device-mobile-landscape", command: function (e) { return e.setDevice("Mobile landscape") }, className: "fa fa-mobile fa-rotate-90" },
      ]
    });
    this.editor.Panels.removePanel('devices-c')
    this.editor.on('load', () => {
        this.load();
      })
  });
  load() {
    setTimeout(()=>{
      this.loading = false;
      this.update()
    },500)
  }
</script>
</div>