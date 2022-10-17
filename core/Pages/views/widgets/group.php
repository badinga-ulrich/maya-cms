<div class="uk-form-row">
    <strong class="uk-text-uppercase">@lang('Pages') <small>(global)</small></strong>
    <div class="uk-margin-small-top">
        <field-boolean bind="group.pages.create" label="@lang('Create')"></field-boolean>
    </div>
    <div class="uk-margin-small-top">
        <field-boolean bind="group.pages.delete" label="@lang('Delete')"></field-boolean>
    </div>
    <div class="uk-margin-small-top">
        <field-boolean bind="group.pages.manage" label="@lang('Manage')"></field-boolean>
    </div>
    @trigger('maya.groups.settings.pages', [&$group])
</div>