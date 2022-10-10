<script>
    window.__pages = {{ json_encode($pages) }};
</script>
<style>
    .show-link {
        background: #007fff;
        border-radius: 2px;
        position: relative;
        top: 6px;
    }
    .show-link-home{
        background: #673ab7;
    }
    .show-link span, .show-link span i{
        color : white !important;
    }
</style>
<div>
    <ul class="uk-breadcrumb">
        <li class="uk-active"><span>@lang('Pages')</span></li>
    </ul>
</div>

<div riot-view>

    <div if="{ ready }">

        <div class="uk-margin uk-clearfix" if="{ App.Utils.count(pages) }">

            <div class="uk-form-icon uk-form uk-text-muted">

                <i class="uk-icon-filter"></i>
                <input class="uk-form-large uk-form-blank" type="text" ref="txtfilter" placeholder="@lang('Filter page...')" aria-label="@lang('Filter page...')" onkeyup="{ updatefilter }">

            </div>

            @hasaccess?('pages', 'create')
            <div class="uk-float-right">
                <a class="uk-button uk-button-large uk-button-primary uk-width-1-1" href="@route('/pages/page')">@lang('Add Page')</a>
            </div>
            @endif

        </div>

        <div class="uk-margin" if="{groups.length}">

            <ul class="uk-tab uk-tab-noborder uk-flex uk-flex-center uk-noselect">
                <li class="{ !group && 'uk-active'}"><a class="uk-text-capitalize { group && 'uk-text-muted'}" onclick="{ toggleGroup }">{ App.i18n.get('All') }</a></li>
                <li class="{ group==parent.group && 'uk-active'}" each="{group in groups}"><a class="uk-text-capitalize { group!=parent.group && 'uk-text-muted'}" onclick="{ toggleGroup }">{ App.i18n.get(group) }</a></li>
            </ul>
        </div>

        <div class="uk-width-medium-1-1 uk-viewport-height-1-3 uk-container-center uk-text-center uk-flex uk-flex-middle uk-flex-center" if="{ !App.Utils.count(pages) }">

            <div class="uk-animation-scale">

                <p>
                    <img class="uk-svg-adjust uk-text-muted" src="@url('pages:icon.svg')" width="80" height="80" alt="Page" data-uk-svg />
                </p>
                <hr>
                <span class="uk-text-large"><strong>@lang('No pages').</strong>

                @hasaccess?('pages', 'create')
                <a href="@route('/pages/page')">@lang('Create one')</a></span>
                @end

            </div>

        </div>


        <div class="uk-grid uk-grid-match uk-grid-gutter uk-grid-width-1-1 uk-grid-width-medium-1-3 uk-grid-width-large-1-4 uk-grid-width-xlarge-1-5 uk-margin-top">

            <div each="{ page,idx in pages }" show="{ ingroup(page.meta) && infilter(page.meta) }">

                <div class="uk-panel uk-panel-box uk-panel-card uk-panel-card-hover">

                    <div class="uk-panel-teaser uk-position-relative">
                        <canvas width="600" height="350"></canvas>
                        <a aria-label="{ page.label }" href="@route('/pages/editor')/{ page.name }" class="uk-position-cover uk-flex uk-flex-middle uk-flex-center">
                            <div class="uk-width-1-4 uk-svg-adjust" style="color:{ (page.meta.color) }">
                                <img riot-src="{ page.meta.icon ? '@url('assets:app/media/icons/')'+page.meta.icon : '@url('pages:icon.svg')'}" alt="icon" data-uk-svg>
                            </div>
                        </a>
                    </div>

                    <div class="uk-grid uk-grid-small">

                        <div data-uk-dropdown="mode:'click'">

                            <a class="uk-icon-cog" style="color: { (page.meta.color) }"></a>

                            <div class="uk-dropdown">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li class="uk-nav-header">@lang('Actions')</li>
                                    <li><a href="@route('/pages/editor')/{ page.name }">@lang('Page Editor')</a></li>
                                    <li if="{ page.meta.allowed.page_edit }" class="uk-nav-divider"></li>
                                    <li if="{ page.meta.allowed.page_edit }"><a href="@route('/pages/page')/{ page.name }">@lang('Edit')</a></li>
                                    @hasaccess?('pages', 'create')
                                    <li><a  class="uk-dropdown-close" href="@route('/pages/page')?from={ page.name }">@lang('Clone')</a></li>
                                    @end
                                    @hasaccess?('pages', 'delete')
                                    <li class="uk-nav-item-danger"><a class="uk-dropdown-close" onclick="{ this.parent.remove }">@lang('Delete')</a></li>
                                    @end
                                </ul>
                            </div>
                        </div>
                        <div class="uk-flex-item-1 uk-text-center uk-text-truncate">
                            <a class="uk-text-bold uk-link-muted" href="@route('/pages/editor')/{page.name}" title="{ page.label }">{ page.label }</a>
                        </div>
                        <div>&nbsp;</div>

                    </div>
                    <div  show="{page.meta.url != '/'}" class="uk-flex-item-1 uk-text-center uk-text-truncate show-link">
                        <span target="_blank" class="uk-text-bold uk-link-muted" title="{ page.meta.url }">
                            <i class="uk-icon-link"></i> { page.meta.url }
                        </span>
                    </div>
                    <div  show="{page.meta.url == '/'}" class="uk-flex-item-1 uk-text-center uk-text-truncate show-link show-link-home">
                        <span target="_blank" class="uk-text-bold uk-link-muted" title="Home page">
                            <i class="uk-icon-home"></i>
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <script type="view/script">

        var $this = this;

        this.ready  = true;
        this.pages = window.__pages;
        this.groups = [];

        this.pages.forEach(function(page) {

            if (page.meta.group) {
                $this.groups.push(page.meta.group);
            }
        });

        if (this.groups.length) {
            this.groups = _.uniq(this.groups.sort());
        }

        remove(e, page) {

            page = e.item.page;

            App.ui.confirm("Are you sure?", function() {

                App.request('/pages/remove_page/'+page.name, {nc:Math.random()}).then(function(data) {

                    App.ui.notify("Page removed", "success");

                    $this.pages.splice(e.item.idx, 1);

                    $this.groups = [];

                    $this.pages.forEach(function(page) {
                        if (page.meta.group) $this.groups.push(page.meta.group);
                    });

                    if ($this.groups.length) {
                        $this.groups = _.uniq($this.groups.sort());
                    }

                    $this.update();
                });
            });
        }

        toggleGroup(e) {
            this.group = e.item && e.item.group || false;
        }

        updatefilter(e) {

        }

        ingroup(collection) {
            return this.group ? (this.group == collection.group) : true;
        }

        infilter(page, value, name, label) {

            if (!this.refs.txtfilter.value) {
                return true;
            }

            value = this.refs.txtfilter.value.toLowerCase();
            name  = [page.name.toLowerCase(), page.label.toLowerCase()].join(' ');

            return name.indexOf(value) !== -1;
        }

    </script>

</div>
