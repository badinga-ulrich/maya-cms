@if(isset($collection['color']) && $collection['color'])
<style>
    .app-header { border-top: 8px {{ $collection['color'] }} solid; }
</style>
@endif

<div>
    <ul class="uk-breadcrumb">
        <li><a href="@route('/collections')">@lang('Collections')</a></li>
        <li data-uk-dropdown="mode:'hover', delay:300">
            <a href="@route('/collections/entries/'.$collection['name'])"><i class="uk-icon-bars"></i> {{ htmlspecialchars(@$collection['label'] ? $collection['label']:$collection['name'], ENT_QUOTES, 'UTF-8') }}</a>

            <div class="uk-dropdown">
                <ul class="uk-nav uk-nav-dropdown">
                    <li class="uk-nav-header">@lang('Actions')</li>
                    @if($app->module('collections')->hasaccess($collection['name'], 'collection_edit'))
                    <li><a href="@route('/collections/collection/'.$collection['name'])">@lang('Edit')</a></li>
                    @endif
                    <li class="uk-nav-divider"></li>
                    <li class="uk-text-truncate"><a href="@route('/collections/export/'.$collection['name'])" download="{{ $collection['name'] }}.collection.json">@lang('Export JSON')</a></li>
                    <li class="uk-text-truncate"><a href="@route('/collections/export/'.$collection['name'])/xlsx" download="{{ $collection['name'] }}.collection.clsx">@lang('Export XLSX')</a></li>
                    @if($app->module('collections')->hasaccess($collection['name'], 'collection_edit'))
                    <li class="uk-text-truncate"><a href="@route('/collections/import/collection/'.$collection['name'])">@lang('Import entries')</a></li>
                    @endif
                </ul>
            </div>
        </li>
    </ul>
</div>

<div class="uk-width-medium-1-2 uk-viewport-height-1-2 uk-container-center uk-flex uk-flex-center uk-flex-middle" riot-view>

    <div class="uk-animation-fade uk-width-1-1">

        <p class="uk-h2">
            @lang('This item is already being edited.')
        </p>
        <div class="uk-panel-box uk-panel-card uk-margin-top">
            <strong class="uk-text-uppercase uk-text-small">@lang('Current editor')</strong>
            <div class="uk-margin-top uk-flex">
                <div>
                    <cp-gravatar size="40" alt="<?=($meta['user']['name'] ? $meta['user']['name'] : $meta['user']['user'])?>"></cp-gravatar>
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
