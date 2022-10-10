
    <!-- Library -->
    
    <link rel="stylesheet" href="@base('htmleditor:assets/css/lib/grapes.min.css')" />
    <link rel="stylesheet" href="@base('htmleditor:assets/css/lib/grapesjs-plugin-toolbox.min.css')" />
    
    <script src="@base('htmleditor:assets/js/lib/grapes.min.js')"></script>
    <script src="@base('htmleditor:assets/js/lib/grapesjs-plugin-toolbox.min.js')"></script>
    <script src="@base('htmleditor:assets/js/lib/grapesjs-plugin-forms.min.js')"></script>
    <script
      src="@base('htmleditor:assets/js/lib/grapesjs-blocks-basic.js')"
      type="text/javascript"
    ></script>

    <!-- Custom -->
    <link rel="stylesheet" href="@base('htmleditor:assets/css/main.css')" />
<div editor-content riot-view>
<div class="loading" show="{ loading }">
  <cp-preloader class="uk-container-center"></cp-preloader>
</div>
<div class="no-page uk-width-medium-1-1 uk-viewport-height-1-3 uk-container-center uk-text-center uk-flex uk-flex-middle uk-flex-center" if="{ !App.Utils.count(pages) }">

    <div class="uk-animation-scale">

        <p>
            <img class="uk-svg-adjust uk-text-muted" src="@base('htmleditor:icon.svg')" width="80" height="80" alt="Collections" data-uk-svg />
        </p>
        <hr>
        <span class="uk-text-large"><strong>@lang('No Pages').</strong>
        @hasaccess?('editor', 'create')
        <a href="@route('/collections/collection')">@lang('Create one')</a></span>
        @end
    </div>

</div>
<div id="gjs">

  <div id="navbar" class="sidenav d-flex flex-column overflow-scroll">
        <div class="my-2 d-flex flex-column" style="flex: 1;">
        <button class="uk-button uk-width-1-1 uk-button-primary" style="border-radius: 0px; height: 40px;">
          <i class="uk-icon-plus"></i>
          @lang('Add Page')
        </button>
        {{ count() }}
          <ul class="uk-list uk-list-line pages">
          </ul>
        </div>
      </div>
      <div class="main-content">
        <nav class="navbar navbar-light">
          <div class="container-fluid">
            <div class="panel__devices"></div>
            <div class="panel__basic-actions"></div>
          </div>
        </nav>
        <div id="editor"></div>
      </div>

      <script type="text/javascript" src="@base('htmleditor:assets/js/main.js')"></script>
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
  this.ready    = false;
  this.loading = true;
  this.pages = [];
  this.on('mount', function() {
    this.load();
  });
  update(){
    
  }
  load() {
    this.loading = true;
    return App.request('/editor/pages', {}).then(function(data){

      this.pages    = data.pages;
      this.count    = data.count;
      
      this.ready    = true;

      this.loading = false;

      this.update();

    }.bind(this))
  }
</script>
</div>