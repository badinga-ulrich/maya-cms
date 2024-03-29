<entries-table>

    <style media="screen">
        .uk-scrollable-box {
            border: none;
            padding-top: 0;
            padding-left: 0;
        }

        .collection-grid-avatar-container {
            border-top: 1px rgba(0,0,0,0.1) solid;
        }

        .collection-grid-avatar {
            transform: translateY(-50%);
            max-width: 40px;
            max-height: 40px;
            border: 1px #fff solid;
            box-shadow: 0 0 40px rgba(0,0,0,0.3);
            border-radius: 50%;
            margin: 0 auto;
            overflow: hidden;
        }

        .collection-grid-avatar .uk-icon-spinner {
            display: none;
        }
    </style>
    <div class="uk-clearfix uk-margin-top" show="{ !loading && (entries.length || filter) }">

        <div class="uk-float-left">

            <div class="uk-button-group">
                <button class="uk-button uk-button-large {listmode=='list' && 'uk-button-primary'}"
                    onclick="{ toggleListMode }"><i class="uk-icon-list"></i></button>
                <button class="uk-button uk-button-large {listmode=='grid' && 'uk-button-primary'}"
                    onclick="{ toggleListMode }"><i class="uk-icon-th"></i></button>
            </div>

        </div>

        <div class="uk-float-left uk-form-select uk-margin-small-left" if="{ !loading && languages.length }">
            <span class="uk-button uk-button-large uk-button-link {lang ? 'uk-text-primary' : 'uk-text-muted'}">
                <i class="uk-icon-globe"></i>
                { lang ? _.find(languages,{'code':lang}).label : App.$data.languageDefaultLabel }
            </span>
            <select onchange="{changelanguage}">
                <option value="" selected="{lang === ''}">{App.$data.languageDefaultLabel}</option>
                <option each="{language,idx in languages}" value="{language.code}" selected="{lang === language.code}">
                    {language.label}</option>
            </select>
        </div>

        <div class="uk-float-right">
            <div class="uk-display-inline-block uk-margin-small-right"
                data-uk-dropdown="mode:'click'" if="{ collection.canEdit && selected.length }">
                <button class="uk-button uk-button-large uk-animation-fade">{App.i18n.get('Batch Action')} <span
                        class="uk-badge uk-badge-contrast uk-margin-small-left">{ selected.length }</span></button>
                <div class="uk-dropdown">
                    <ul class="uk-nav uk-nav-dropdown uk-dropdown-close">
                        <li class="uk-nav-header">{App.i18n.get('Actions')}</li>
                        <li><a onclick="{ batchedit }">{App.i18n.get('Edit')}</a></li>
                        <li if="{collection.canDelete}" class="uk-nav-item-danger"><a
                                onclick="{ removeselected }">{App.i18n.get('Delete')}</a></li>
                    </ul>
                </div>
            </div>
            <a if="{collection.canCreate}" class="uk-button uk-button-large uk-button-primary"
        href="{collection.createEntryUrl}?link={collection.parent}&amp;id={ entry._id }">{App.i18n.get('Add Entry')}</a>
        </div>
    </div>
    <div class="uk-margin-top">
        <div class="uk-width-medium-1-3 uk-viewport-height-1-2 uk-container-center uk-text-center uk-flex uk-flex-center uk-flex-middle"
            if="{ loading }">
            <div class="uk-animation-fade uk-text-center">
                <cp-preloader class="uk-container-center"></cp-preloader>
            </div>
        </div>

        <div class="uk-width-medium-1-3 uk-viewport-height-1-2 uk-container-center uk-text-center uk-flex uk-flex-center uk-flex-middle"
            if="{ !loading && (!entries || !entries.length) }">
            <div class="uk-animation-scale">
                <img class="uk-svg-adjust" src="{collection.icon}" width="50" alt="icon" data-uk-svg>
                <div class="uk-margin-top uk-text-small uk-text-muted" if="{collection.description}">
                    {collection.description}
                </div>
                <hr>
                <span class="uk-text-large"><strong>{App.i18n.get('No entries')}.</strong> <a
                        href="{collection.createEntryUrl}">{App.i18n.get('Create an entry')}.</a></span>
            </div>
        </div>
        <div class="uk-margin-top" show="{ !loading  }">
            <div class="uk-text-xlarge uk-text-muted uk-viewport-height-1-3 uk-flex uk-flex-center uk-flex-middle"
                if="{ !entries.length && !loading }">
                <div>{App.i18n.get('No entries found')}</div>
            </div>
            <div class="uk-grid uk-grid-match uk-grid-width-medium-1-4 uk-flex-center"
                if="{ entries.length && !loading && listmode=='grid' }">
                <div class="uk-grid-margin" each="{entry,idx in entries}">

                    <div class="uk-panel uk-panel-box uk-panel-card uk-panel-card-hover">

                        <div class="uk-position-relative uk-nbfc">
                            <canvas width="400" height="250"></canvas>
                            <div class="uk-position-cover uk-flex uk-flex-center uk-flex-middle">

                                <cp-thumbnail src="{ parent.isImageField(entry) }" width="400" height="250"
                                    if="{ parent.isImageField(entry) }"></cp-thumbnail>

                                <div class="uk-svg-adjust uk-text-primary"
                                    style="color:{ collection['color'] } !important;"
                                    if="{ !parent.isImageField(entry) }">
                                    <img src="{collection['icon']}" width="80" alt="icon" data-uk-svg>
                                </div>
                            </div>
                            <a class="uk-position-cover" href="{collection.createEntryUrl}/{ entry._id }"></a>
                        </div>
                        <div class="collection-grid-avatar-container">
                            <div class="collection-grid-avatar">
                                <cp-account account="{entry._mby || entry._by}" label="{false}" size="40"
                                    if="{entry._mby || entry._by}"></cp-account>
                                <cp-gravatar alt="?" size="40" if="{!(entry._mby || entry._by)}"></cp-gravatar>
                            </div>
                        </div>
                        <div class="uk-flex uk-flex-middle uk-margin-small-top">

                            <div class="uk-flex-item-1 uk-margin-small-right uk-text-small">
                                <span class="uk-text-success uk-margin-small-right">{ App.Utils.dateformat( new Date(
                                    1000 * entry._created )) }</span>
                                <span class="uk-text-primary">{ App.Utils.dateformat( new Date( 1000 * entry._modified
                                    )) }</span>
                            </div>

                            <span data-uk-dropdown="mode:'click', pos:'bottom-right'">

                                <a class="uk-icon-bars"></a>

                                <div class="uk-dropdown uk-dropdown-flip">
                                    <ul class="uk-nav uk-nav-dropdown">
                                        <li class="uk-nav-header">{App.i18n.get('Actions')}</li>
                                        <li if="{collection.canEdit}"><a
                                                href="{collection.createEntryUrl}/{ entry._id }">{App.i18n.get('Edit')}</a>
                                        </li>
                                        <li if="{!collection.canEdit}"><a
                                                href="{collection.createEntryUrl}/{ entry._id }">{App.i18n.get('View')}</a>
                                        </li>
                                        <li if="{collection.canDelete}" class="uk-nav-item-danger"><a
                                                class="uk-dropdown-close"
                                                onclick="{ parent.remove }">{App.i18n.get('Delete')}</a></li>
                                        <li if="{collection.canCreate}" class="uk-nav-divider"></li>
                                        <li if="{collection.canCreate}"><a class="uk-dropdown-close"
                                                onclick="{ parent.duplicateEntry }">{App.i18n.get('Duplicate')}</a></li>
                                    </ul>
                                </div>
                            </span>
                        </div>

                        <div class="uk-margin-top uk-scrollable-box">
                            <div class="uk-margin-small-bottom" each="{field,idy in parent.fields}"
                                if="{ field.name != '_modified' && field.name != '_created' }">
                                <span class="uk-text-small uk-text-uppercase uk-text-muted">{ field.label || field.name
                                    }</span>
                                <a class="uk-link-muted uk-text-small uk-display-block uk-text-truncate"
                                href="{collection.createEntryUrl}/{ entry._id }">
                                    <raw content="{ App.Utils.renderValue(field.type, entry[field.name], field) }"
                                        if="{entry[field.name] !== undefined}"></raw>
                                    <span class="uk-icon-eye-slash uk-text-muted"
                                        if="{entry[field.name] === undefined}"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class=" uk-overflow-container uk-viewport-height-1-3"
                if="{ entries.length && !loading && listmode=='list' }">
                <table class="uk-table uk-table-tabbed uk-table-striped">
                    <thead>
                        <tr>
                            <th width="20"><input class="uk-checkbox" type="checkbox" data-check="all"></th>
                            <th width="{field.name == '_modified' || field.name == '_created' ? '100':''}"
                                class="uk-text-small" each="{field,idx in fields}">

                                <a class="uk-link-muted uk-noselect { (parent.sort[field.name] || parent.sort[field.name+'.display']) ? 'uk-text-primary':'' }"
                                    onclick="{ parent.updatesort }" data-sort="{ field.name }">

                                    { field.label || field.name }

                                    <span if="{(parent.sort[field.name] || parent.sort[field.name+'.display'])}"
                                        class="uk-icon-long-arrow-{ (parent.sort[field.name] == 1 || parent.sort[field.name+'.display']==1) ? 'up':'down'}"></span>
                                </a>
                            </th>
                            <th width="20"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr each="{entry,idx in entries}">
                            <td><input class="uk-checkbox" type="checkbox" data-check data-id="{ entry._id }"></td>
                            <td class="uk-text-truncate" each="{field,idy in parent.fields}"
                                if="{ field.name != '_modified' && field.name != '_created' }">
                                <a class="uk-link-muted" href="{collection.createEntryUrl}/{ entry._id }">
                                    <raw content="{ App.Utils.renderValue(field.type, entry[field.name], field) }"
                                        if="{entry[field.name] !== undefined}"></raw>
                                    <span class="uk-icon-eye-slash uk-text-muted"
                                        if="{entry[field.name] === undefined}"></span>
                                </a>
                            </td>
                            <td><span class="uk-badge uk-badge-outline uk-text-muted">{ App.Utils.dateformat( new Date(
                                    1000 * entry._created )) }</span></td>
                            <td><span class="uk-badge uk-badge-outline uk-text-primary">{ App.Utils.dateformat( new
                                    Date( 1000 * entry._modified )) }</span></td>
                            <td>
                                <span data-uk-dropdown="mode:'click'">
                                    <a class="uk-icon-bars"></a>
                                    <div class="uk-dropdown uk-dropdown-flip">
                                        <ul class="uk-nav uk-nav-dropdown">
                                            <li class="uk-nav-header">{App.i18n.get('Actions')}</li>
                                            <li if="{collection.canEdit}"><a
                                                    href="{collection.createEntryUrl}/{ entry._id }">{App.i18n.get('Edit')}</a>
                                            </li>
                                            <li if="{!collection.canEdit}"><a
                                                    href="{collection.createEntryUrl}/{ entry._id }">{App.i18n.get('View')}</a>
                                            </li>
                                            <li if="{collection.canDelete}" class="uk-nav-item-danger"><a
                                                    class="uk-dropdown-close"
                                                    onclick="{ parent.remove }">{App.i18n.get('Delete')}</a></li>
                                            <li if="{collection.canCreate}" class="uk-nav-divider"></li>
                                            <li if="{collection.canCreate}"><a class="uk-dropdown-close"
                                                    onclick="{ parent.duplicateEntry }">{App.i18n.get('Duplicate')}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="uk-margin uk-flex uk-flex-middle" if="{ !loading && pages > 1 }">
                <ul class="uk-breadcrumb uk-margin-remove">
                    <li class="uk-active"><span>{ page }</span></li>
                    <li data-uk-dropdown="mode:'click'">
                        <a><i class="uk-icon-bars"></i> { pages }</a>
                        <div class="uk-dropdown">
                            <strong class="uk-text-small">{App.i18n.get('Pages')}</strong>
                            <div class="uk-margin-small-top { pages > 5 ? 'uk-scrollable-box':'' }">
                                <ul class="uk-nav uk-nav-dropdown">
                                    <li class="uk-text-small" each="{k,v in new Array(pages)}"><a
                                            class="uk-dropdown-close"
                                            onclick="{ parent.loadpage.bind(parent, v+1) }">{App.i18n.get('Page')} {v +
                                            1}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="uk-button-group uk-margin-small-left">
                    <a class="uk-button uk-button-small" onclick="{ loadpage.bind(this, page-1) }"
                        if="{page-1 > 0}">{App.i18n.get('Previous')}</a>
                    <a class="uk-button uk-button-small" onclick="{ loadpage.bind(this, page+1) }"
                        if="{page+1 <= pages}">{App.i18n.get('Next')}</a>
                </div>
                <div class="uk-margin-small-right" data-uk-dropdown="mode:'click'">
                    <a class="uk-button uk-button-link uk-button-small uk-text-muted">{limit}</a>
                    <div class="uk-dropdown">
                        <ul class="uk-nav uk-nav-dropdown">
                            <li class="uk-nav-header">{App.i18n.get('Show')}</li>
                            <li><a onclick="{updateLimit.bind(this, 20)}">20</a></li>
                            <li><a onclick="{updateLimit.bind(this, 40)}">40</a></li>
                            <li><a onclick="{updateLimit.bind(this, 80)}">80</a></li>
                            <li><a onclick="{updateLimit.bind(this, 100)}">100</a></li>
                            <li class="uk-nav-divider"></li>
                            <li><a onclick="{updateLimit.bind(this, null)}">{App.i18n.get('All')}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var $this = this,
            $root = App.$($this.root);
        $this.selectedLink = opts.selectedlink;
        $this.name = opts.name || "";
        $this.entries = [];
        $this.initial = true;
        $this.collection = opts.collection || false;
        $this.entry = opts.entry || {};
        $this.filter = {};
        $this.count = 0;
        $this.page = 1;
        $this.limit = 20;
        $this.fieldsidx = {};
        $this.imageField = null;
        $this.languages = App.$data.languages;
        if ($this.languages.length) {
            $this.lang = App.session.get('collections.entry.' + $this.collection._id + '.lang', '');
        }
        $this.sort = {};
        $this.sort[$this.collection.sort.column] = $this.collection.sort.dir;
        $this.fields = $this.collection.fields.filter(function (field) {

            if (!CollectionHasFieldAccess(field)) return false;

            $this.fieldsidx[field.name] = field;

            if (!$this.imageField && (field.type == 'image' || field.type == 'asset')) {
                $this.imageField = field;
            }

            return field.lst;
        });

        $this.fieldsidx['_created'] = {
            name: '_created',
            'label': App.i18n.get("Created"),
            type: 'text'
        };
        $this.fieldsidx['_modified'] = {
            name: '_modified',
            'label': App.i18n.get("Modified"),
            type: 'text'
        };

        $this.fields.push($this.fieldsidx['_created']);
        $this.fields.push($this.fieldsidx['_modified']);
        $this.selected = [];
        $this.listmode = App.session.get('collections.entries.' + $this.collection.name + '.listmode', 'list');
        $this.on('mount', function () {
            $this.load(true);
            $this.update();
        })
        json() {
            return JSON.stringify($this.entries, null, 2);
        }
        updateLimit(limit) {
            this.limit = limit;
            this.page = 1;
            this.load();
        }
        updatesort(e, field) {
            e.preventDefault();
            field = e.target.getAttribute('data-sort');
            if (!field) {
                return;
            }
            var col = field;
            switch (this.fieldsidx[field].type) {
                case 'collectionlink':
                    col = field + '.display';
                    break;
                case 'location':
                    col = field + '.address';
                    break;
                default:
                    col = field;
            }
            if (e.metaKey || e.ctrlKey) {
                // multi select
            } else {

                var sort = {};

                if (this.sort[col]) {
                    sort[col] = this.sort[col];
                }

                this.sort = sort;
            }

            if (!this.sort[col]) {
                this.sort[col] = 1;
            } else {
                this.sort[col] = this.sort[col] == 1 ? -1 : 1;
            }
            this.entries = [];
            this.load();
        }
        loadpage(page) {
            this.page = page > this.pages ? this.pages : page;
            this.load();
        }
        load(initial) {

            var options = {
                sort: this.sort
            };

            if (this.lang) {
                options.lang = this.lang;
            }
            options.filter = {
                "$and" : []
            };
            window.$this = $this;
            $filterEntry = {};
            $filterEntry[$this.selectedLink.name + "._id"] = this.entry._id;
            options.filter["$and"].push($filterEntry);
            if (this.filter && Object.keys(this.filter).length) {
                options.filter["$and"].push(this.filter);
            }
            if(options.filter["$and"].length == 1){
                options.filter = options.filter["$and"][0];
            }

            if (this.limit) {
                options.limit = this.limit;
            }

            options.skip = (this.page - 1) * this.limit;

            this.loading = true;

            // if (!initial) {

            //     window.history.pushState(
            //         null, null,
            //         App.route(['/collections/entries/', this.collection.name, '?q=', JSON.stringify({
            //             page: this.page || null,
            //             filter: this.filter || null,
            //             sort: this.sort || null,
            //             limit: this.limit
            //         })].join(''))
            //     );
            // }

            return App.request('/collections/find', {
                collection: this.collection.name,
                options: options
            }).then(function (data) {

                window.scrollTo(0, 0);

                this.entries = data.entries;
                this.pages = data.pages;
                this.page = data.page;
                this.count = data.count;

                // this.checkselected();
                this.loading = false;
                this.update();

            }.bind(this))
        }
        editOrView(e, entry, idx) {
            if ($this.collection.canEdit)
                alert("Edit");
            else
                alert("view");
        }
        remove(e, entry, idx) {

            entry = e.item.entry
            idx = e.item.idx;

            App.ui.confirm("Are you sure?", function () {

                App.request('/collections/delete_entries/' + $this.collection.name, {
                    filter: {
                        '_id': entry._id
                    }
                }).then(function (data) {

                    App.ui.notify("Entry removed", "success");

                    $this.entries.splice(idx, 1);

                    if ($this.pages > 1 && !$this.entries.length) {
                        $this.page = $this.page == 1 ? 1 : $this.page - 1;
                        $this.load();
                        return;
                    }

                    $this.update();

                    $this.checkselected();
                });

            }.bind(this));
        }
        newEntry() {
            alert("new data");
        }
        duplicateEntry(e, collection, entry, idx) {

            collection = this.collection.name;
            entry = App.$.extend({}, e.item.entry);
            idx = e.item.idx;

            delete entry._id;

            App.request('/collections/save_entry/' + this.collection.name, {
                "entry": entry
            }).then(function (entry) {

                if (entry) {

                    $this.entries.unshift(entry);
                    App.ui.notify("Entry duplicated", "success");
                    $this.update();
                }
            });
        }
        toggleListMode() {
            this.listmode = this.listmode == 'list' ? 'grid' : 'list';
            App.session.set('collections.entries.' + this.collection.name + '.listmode', this.listmode);
        }
        isImageField(entry) {
            if (!this.imageField) {
                return false;
            }
            var data = entry[this.imageField.name];
            if (!data) {
                return false;
            }
            switch (this.imageField.type) {
                case 'asset':
                    if (data.mime && data.mime.match(/^image\//)) {
                        return ASSETS_URL + data.path;
                    }
                    break;
                case 'image':
                    if (data.path) {
                        return data.path.match(/^(http\:|https\:|\/\/)/) ? data.path : SITE_URL + '/' + data.path;
                    }
                    break;
            }
            return false;
        }
    </script>

</entries-table>