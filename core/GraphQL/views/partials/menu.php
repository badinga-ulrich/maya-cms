
<li class="uk-grid-margin">
    <a class="uk-display-block uk-panel-card-hover uk-panel-box uk-panel-space {{$module_active ? 'uk-bg-primary uk-contrast' : ''}}" href="@route('/graphql/playground')">
        <div class="uk-svg-adjust">
            <img class="uk-margin-small-right inherit-color" src="@base('graphql:icon.svg')" width="40" height="40" data-uk-svg alt="graphql" />
        </div>
        <div class="uk-text-truncate uk-text-small uk-margin-small-top">@lang('GraphQL')</div>
    </a>
</li>