<div>
    <ul class="uk-breadcrumb">
        <li class="uk-active"><span>@lang('Finder')</span></li>
    </ul>
</div>

<div riot-view>
    <cp-finder root="{{ $app->module("maya")->getGroupVar("media.path", '/') }}"></cp-finder>
</div>
