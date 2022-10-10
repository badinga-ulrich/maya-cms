<div>

    <div class="uk-panel-box uk-panel-card">

        <div class="uk-panel-box-header uk-flex uk-flex-middle">
            <strong class="uk-panel-box-header-title uk-flex-item-1">
                @lang('Pages')

                @hasaccess?('pages', 'create')
                <a href="@route('/pages/page')" class="uk-icon-plus uk-margin-small-left" title="@lang('Create Page')" data-uk-tooltip></a>
                @end
            </strong>

            @if(count($pages))
            <span class="uk-badge uk-flex uk-flex-middle"><span>{{ count($pages) }}</span></span>
            @endif
        </div>

        @if(count($pages))

            <div class="uk-margin">

                <ul class="uk-list uk-list-space uk-margin-top">
                    @foreach(array_slice($pages, 0, count($pages) > 5 ? 5: count($pages)) as $page)
                    <li class="uk-text-truncate">
                        <a class="uk-link-muted" href="@route('/pages/form/'.$page['name'])">

                            <img class="uk-margin-small-right uk-svg-adjust" src="@url(isset($page['icon']) && $page['icon'] ? 'assets:app/media/icons/'.$page['icon']:'pages:icon.svg')" width="18px" alt="icon" data-uk-svg>

                            {{ htmlspecialchars(@$page['label'] ? $page['label'] : $page['name'], ENT_QUOTES, 'UTF-8') }}
                        </a>
                    </li>
                    @endforeach
                </ul>

            </div>

            @if(count($pages) > 5)
            <div class="uk-panel-box-footer uk-text-center">
                <a class="uk-button uk-button-small uk-button-link" href="@route('/pages')">@lang('Show all')</a>
            </div>
            @endif

        @else

            <div class="uk-margin uk-text-center uk-text-muted">

                <p>
                    <img src="@url('pages:icon.svg')" width="30" height="30" alt="Pages" data-uk-svg />
                </p>

                @lang('No pages')

            </div>

        @endif

    </div>

</div>
