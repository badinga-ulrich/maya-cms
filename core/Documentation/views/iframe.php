<div class="uk-container-breakout">
    <iframe style="height:100vh" id="documentation" src="@route('/documentation')" width="100%" frameborder="0"></iframe>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {

        let headerHeight = App.$('.app-header').outerHeight(),
            marginTop    = App.$(documentation).offset().top - headerHeight;

        documentation.style.height = `calc(100vh - ${headerHeight}px`;
        documentation.style.marginTop = `-${marginTop}px`;
    });

</script>

<style>
    body { padding: 0; }
</style>