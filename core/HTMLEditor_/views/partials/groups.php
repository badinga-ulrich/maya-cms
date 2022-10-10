<div class="uk-form-row">
    <strong class="uk-text-uppercase">@lang('editor') <small>(global)</small></strong>
    <div class="uk-margin-small-top">
        <field-boolean bind="group.editor.create" label="@lang('Create')"></field-boolean>
    </div>
    <div class="uk-margin-small-top">
        <field-boolean bind="group.editor.delete" label="@lang('Delete')"></field-boolean>
    </div>
    <div class="uk-margin-small-top">
        <field-boolean bind="group.editor.manage" label="@lang('Manage')"></field-boolean>
    </div>
    @trigger('maya.groups.settings.editor', [&$group])
</div>