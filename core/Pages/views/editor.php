
    <!-- Library -->
    
    <link rel="stylesheet" href="@base('pages:assets/css/lib/grapes.min.css')" />
    <link rel="stylesheet" href="@base('pages:assets/css/lib/grapick.min.css')" />
    <link rel="stylesheet" href="@base('pages:assets/css/lib/grapesjs-plugin-toolbox.min.css')" />
    <link rel="stylesheet" href="@base('pages:assets/css/lib/grapesjs-preset-webpage.min.css')" />
    
    <script src="@base('pages:assets/js/lib/grapes.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-preset-webpage.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-custom-code.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-component-countdown.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-tabs.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-touch.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-parser-postcss.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-tooltip.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-tui-image-editor.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-typed.min.js')"></script>
    <script src="@base('pages:assets/js/lib/grapesjs-style-bg.min.js')"></script>
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
    var lp = './img/';
    var plp = 'https://via.placeholder.com/350x250/';
    var images = [
      lp+'team1.jpg', lp+'team2.jpg', lp+'team3.jpg', plp+'78c5d6/fff/image1.jpg', plp+'459ba8/fff/image2.jpg', plp+'79c267/fff/image3.jpg',
      plp+'c5d647/fff/image4.jpg', plp+'f28c33/fff/image5.jpg', plp+'e868a2/fff/image6.jpg', plp+'cc4360/fff/image7.jpg',
      lp+'work-desk.jpg', lp+'phone-app.png', lp+'bg-gr-v.png'
    ];
    this.editor = grapesjs.init({
        container : '#gjs #editor',
        showOffsets: true,
        assetManager: {
          embedAsBase64: true,
          assets: images
        },
        selectorManager: { componentFirst: true },
        styleManager: {
          sectors: [{
              name: 'General',
              properties:[
                {
                  extend: 'float',
                  type: 'radio',
                  default: 'none',
                  options: [
                    { value: 'none', className: 'fa fa-times'},
                    { value: 'left', className: 'fa fa-align-left'},
                    { value: 'right', className: 'fa fa-align-right'}
                  ],
                },
                'display',
                { extend: 'position', type: 'select' },
                'top',
                'right',
                'left',
                'bottom',
              ],
            }, {
                name: 'Dimension',
                open: false,
                properties: [
                  'width',
                  {
                    id: 'flex-width',
                    type: 'integer',
                    name: 'Width',
                    units: ['px', '%'],
                    property: 'flex-basis',
                    toRequire: 1,
                  },
                  'height',
                  'max-width',
                  'min-height',
                  'margin',
                  'padding'
                ],
              },{
                name: 'Typography',
                open: false,
                properties: [
                    'font-family',
                    'font-size',
                    'font-weight',
                    'letter-spacing',
                    'color',
                    'line-height',
                    {
                      extend: 'text-align',
                      options: [
                        { id : 'left',  label : 'Left',    className: 'fa fa-align-left'},
                        { id : 'center',  label : 'Center',  className: 'fa fa-align-center' },
                        { id : 'right',   label : 'Right',   className: 'fa fa-align-right'},
                        { id : 'justify', label : 'Justify',   className: 'fa fa-align-justify'}
                      ],
                    },
                    {
                      property: 'text-decoration',
                      type: 'radio',
                      default: 'none',
                      options: [
                        { id: 'none', label: 'None', className: 'fa fa-times'},
                        { id: 'underline', label: 'underline', className: 'fa fa-underline' },
                        { id: 'line-through', label: 'Line-through', className: 'fa fa-strikethrough'}
                      ],
                    },
                    'text-shadow'
                ],
              },{
                name: 'Decorations',
                open: false,
                properties: [
                  'opacity',
                  'border-radius',
                  'border',
                  'box-shadow',
                  'background', // { id: 'background-bg', property: 'background', type: 'bg' }
                ],
              },{
                name: 'Extra',
                open: false,
                buildProps: [
                  'transition',
                  'perspective',
                  'transform'
                ],
              },{
                name: 'Flex',
                open: false,
                properties: [{
                  name: 'Flex Container',
                  property: 'display',
                  type: 'select',
                  defaults: 'block',
                  list: [
                    { value: 'block', name: 'Disable'},
                    { value: 'flex', name: 'Enable'}
                  ],
                },{
                  name: 'Flex Parent',
                  property: 'label-parent-flex',
                  type: 'integer',
                },{
                  name: 'Direction',
                  property: 'flex-direction',
                  type: 'radio',
                  defaults: 'row',
                  list: [{
                    value: 'row',
                    name: 'Row',
                    className: 'icons-flex icon-dir-row',
                    title: 'Row',
                  },{
                    value: 'row-reverse',
                    name: 'Row reverse',
                    className: 'icons-flex icon-dir-row-rev',
                    title: 'Row reverse',
                  },{
                    value: 'column',
                    name: 'Column',
                    title: 'Column',
                    className: 'icons-flex icon-dir-col',
                  },{
                    value: 'column-reverse',
                    name: 'Column reverse',
                    title: 'Column reverse',
                    className: 'icons-flex icon-dir-col-rev',
                  }],
                },{
                  name: 'Justify',
                  property: 'justify-content',
                  type: 'radio',
                  defaults: 'flex-start',
                  list: [{
                    value: 'flex-start',
                    className: 'icons-flex icon-just-start',
                    title: 'Start',
                  },{
                    value: 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-just-end',
                  },{
                    value: 'space-between',
                    title: 'Space between',
                    className: 'icons-flex icon-just-sp-bet',
                  },{
                    value: 'space-around',
                    title: 'Space around',
                    className: 'icons-flex icon-just-sp-ar',
                  },{
                    value: 'center',
                    title: 'Center',
                    className: 'icons-flex icon-just-sp-cent',
                  }],
                },{
                  name: 'Align',
                  property: 'align-items',
                  type: 'radio',
                  defaults: 'center',
                  list: [{
                    value: 'flex-start',
                    title: 'Start',
                    className: 'icons-flex icon-al-start',
                  },{
                    value: 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-al-end',
                  },{
                    value: 'stretch',
                    title: 'Stretch',
                    className: 'icons-flex icon-al-str',
                  },{
                    value: 'center',
                    title: 'Center',
                    className: 'icons-flex icon-al-center',
                  }],
                },{
                  name: 'Flex Children',
                  property: 'label-parent-flex',
                  type: 'integer',
                },{
                  name: 'Order',
                  property: 'order',
                  type: 'integer',
                  defaults: 0,
                  min: 0
                },{
                  name: 'Flex',
                  property: 'flex',
                  type: 'composite',
                  properties  : [{
                    name: 'Grow',
                    property: 'flex-grow',
                    type: 'integer',
                    defaults: 0,
                    min: 0
                  },{
                    name: 'Shrink',
                    property: 'flex-shrink',
                    type: 'integer',
                    defaults: 0,
                    min: 0
                  },{
                    name: 'Basis',
                    property: 'flex-basis',
                    type: 'integer',
                    units: ['px','%',''],
                    unit: '',
                    defaults: 'auto',
                  }],
                },{
                  name: 'Align',
                  property: 'align-self',
                  type: 'radio',
                  defaults: 'auto',
                  list: [{
                    value: 'auto',
                    name: 'Auto',
                  },{
                    value: 'flex-start',
                    title: 'Start',
                    className: 'icons-flex icon-al-start',
                  },{
                    value   : 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-al-end',
                  },{
                    value   : 'stretch',
                    title: 'Stretch',
                    className: 'icons-flex icon-al-str',
                  },{
                    value   : 'center',
                    title: 'Center',
                    className: 'icons-flex icon-al-center',
                  }],
                }]
              }
            ],
        },
        plugins: [
          "gjs-blocks-basic", 
          "grapesjs-plugin-toolbox", 
          "grapesjs-plugin-forms",
          "grapesjs-custom-code",
          "grapesjs-component-countdown",
          "grapesjs-touch",
          "grapesjs-tabs",
          "grapesjs-parser-postcss",
          "grapesjs-tooltip",
          "grapesjs-tui-image-editor",
          "grapesjs-typed",
          "grapesjs-style-bg",
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
                  App.callmodule('pages:setFieldValue', [$this.page.name, "html", editor.getHtml().replace("<body","<div").replace("</body>","</div>"), {
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
          'grapesjs-preset-webpage'
        ],
        showDevices: false,
        fromElement : true,
        pluginsOpts: {
          'grapesjs-preset-webpage': {},
          "gjs-blocks-basic": {},
          "grapesjs-plugin-toolbox" :{},
          'gjs-blocks-basic': { flexGrid: true },
          'grapesjs-tui-image-editor': {
            script: [
              "@base('pages:assets/js/lib/tui-code-snippet.min.js')",
              "@base('pages:assets/js/lib/tui-color-picker.min.js')",
              "@base('pages:assets/js/lib/tui-image-editor.min.js')"
            ],
            style: [
              "@base('pages:assets/css/lib/tui-color-picker.min.css')",
              "@base('pages:assets/css/lib/tui-image-editor.min.css')"
            ],
          },
          'grapesjs-tabs': {
            tabsBlock: { category: 'Extra' }
          },
          'grapesjs-typed': {
            block: {
              category: 'Extra',
              content: {
                type: 'typed',
                'type-speed': 40,
                strings: [
                  'Text row one',
                  'Text row two',
                  'Text row three',
                ],
              }
            }
          },
        },
        storageManager: {
          type: 'maya-store',
          stepsBeforeSave: 1
        }
    });
    this.editor.I18n.addMessages({
        en: {
          styleManager: {
            properties: {
              'background-repeat': 'Repeat',
              'background-position': 'Position',
              'background-attachment': 'Attachment',
              'background-size': 'Size',
            }
          },
        }
      });
    this.editor.Panels.removeButton('options','export-template')
    //this.editor.Panels.removeButton('options','gjs-open-import-webpage')
    this.editor.Panels.addPanel({
      id: 'devices', buttons: [
          { id: "back", command: function (e) { return document.location= "@route('/pages')"; },className: "editor-back", label: '<i class="fa fa-chevron-left"></i>&nbsp;{{$page["name"]}}&nbsp;&nbsp;&nbsp;&nbsp;', },
          { id: "set-device-desktop", command: function (e) { return e.setDevice("Desktop") }, className: "fa fa-desktop", active: 1 },
          { id: "set-device-tablet", command: function (e) { return e.setDevice("Tablet") }, className: "fa fa-tablet" },
          { id: "set-device-mobile", command: function (e) { return e.setDevice("Mobile portrait") }, className: "fa fa-mobile" },
          { id: "set-device-mobile-landscape", command: function (e) { return e.setDevice("Mobile landscape") }, className: "fa fa-mobile fa-rotate-90" },
      ]
    });
    this.editor.Panels.removePanel('devices-c');

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