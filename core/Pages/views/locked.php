
@if($page['color'])
<style>
    .app-header { border-top: 8px {{ $page['color'] }} solid; }
</style>
@endif

<div>
    <ul class="uk-breadcrumb">
        <li><a href="@route('/pages')">@lang('Pages')</a></li>
        <li class="uk-active" data-uk-dropdown>

            <a><i class="uk-icon-bars"></i> {{ htmlspecialchars(@$page['label'] ? $page['label']:$page['name'], ENT_QUOTES, 'UTF-8') }}</a>

            @if($app->module('pages')->hasaccess($page['name'], 'edit'))
            <div class="uk-dropdown">
                <ul class="uk-nav uk-nav-dropdown">
                    <li class="uk-nav-header">@lang('Actions')</li>
                    <li><a href="@route('/pages/page/'.$page['name'])">@lang('Edit')</a></li>
                </ul>
            </div>
            @endif

        </li>
    </ul>
</div>

<div class="uk-width-medium-1-2 uk-viewport-height-1-2 uk-container-center uk-flex uk-flex-center uk-flex-middle" riot-view>

    <div class="uk-animation-fade uk-width-1-1">

        <p class="uk-h2">
            @lang('This page is already being edited.')
        </p>
        <div class="uk-panel-box uk-panel-card uk-margin-top">
            <strong class="uk-text-uppercase uk-text-small">@lang('Current editor')</strong>
            <div class="uk-margin-top uk-flex">
                <div>
                    <cp-gravatar size="30" alt="<?=($meta['user']['name'] ? $meta['user']['name'] : $meta['user']['user'])?>"></cp-gravatar>
                </div>
                <div class="uk-margin-left">
                    <span><?=($meta['user']['name'] ? $meta['user']['name'] : $meta['user']['user'])?></span><br />
                    <span class="uk-text-muted"><?=($meta['user']['email'])?></span>
                </div>
            </div>

            @render('maya:views/_partials/unlock.php', ['resourceId' => $meta['rid']])
        </div>

    </div>

</div>
