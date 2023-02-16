<entry-editor>

    <style>
        .header,
        .content {
            padding: 20px;
        }

        .content {
            overflow: auto;
        }
    </style>

    <div class="uk-offcanvas" ref="offcanvas">
        <div class="uk-offcanvas-bar uk-offcanvas-bar-flip uk-width-3-4 uk-flex uk-flex-column">
            <div class="uk-flex uk-flex-middle header">
                <span class="uk-badge uk-badge-contrast">{App.i18n.get('Edit entry')}</span>
                <!-- <a class="uk-margin-left" onclick="{ load }"><i class="uk-icon-refresh"></i></a> -->
                <div class="uk-flex-item-1 uk-text-right">
                    <a class="uk-offcanvas-close uk-link-muted uk-icon-close"></a>
                </div>
            </div>
            <div ref="inserform" class="content uk-flex-item-1" style="
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            ">
                <cp-preloader class="uk-container-center uk-margin-large-top" if="{loading}"></cp-preloader>
                <div ref="inserform" class="uk-form" style="
                    flex: 1;
                    align-self: stretch;
                "> </div>
            </div>
        </div>

    </div>

    <script>

        var $this = this;
        this.loading = true;
        this.idle = null;

        this.on('mount', function() {
            App.$(this.root).on('hide.uk.offcanvas', ()=> {
                clearInterval(this.idle);
                setTimeout(this.update, 100);
            });
        });

        load() {
            return new Promise(async ok=>{
                this.loading = true;
                this.update();

                // lock resource
                clearInterval(this.idle);
                this.idle = setInterval(() =>{
                    if (!(this.entry._id && this.canEdit)) return;
                    Maya.lockResource(this.entry._id, function(e){
                        window.location.href = App.route('/collections/entry/'+$this.collection.name+'/'+$this.entry._id);
                    });
                    clearInterval(this.idle);
                }, 60000);
                if(this.parent){
                    await this.parent.reload();
                }
                this.loading = false;
                setTimeout(this.update, 1000);
                ok();
            })
        }
        hasFieldAccess(e){
            return this.parent?.hasFieldAccess(e) ?? false
        }
        checkVisibilityRule(e){
            return this.parent?.checkVisibilityRule(e) ?? false
        }

        submit(e){
            return this.parent?.submit(e) ?? false
        }

        show(el) {
            console.log(el);
            if(!this.form){
                this.form = $(el);
                this.form.appendTo($(this.refs.inserform));
                this.form.show();
            }
            UIkit.offcanvas.show(this.refs.offcanvas);
            this.load().then(e=>{
                setTimeout(this.update, 100);
            });

        }
    </script>

</entry-editor>