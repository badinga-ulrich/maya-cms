

this.on('mount', function() {

  if($this.user["api_key"]){
    if(typeof(SSE) !== "undefined") {
      const source = new SSE(App.route('/api/sse/chanel/events/*')+'?token='+$this.user["api_key"], {withCredentials: true});
      var  terminal = new Terminal({
        theme : {
          foreground : "#000",
          background : "#fafbfc"
        }
      });
      terminal.open(document.getElementById("sse-console"));
      terminal.loadAddon(new CanvasAddon.CanvasAddon());
      terminal.focus();
      terminal.writeln('\x1b[1;35mONLY GLOBAL SSE EVENTS WILL LOG HERE\x1b[0m')

      source.addEventListener('*', function (event) {
        // while($("#sse-console > *").length > 2){
        //   $("#sse-console > *").last().remove()
        // }
        // var el = $("<pre>["+moment().format("L H:m:s")+"]["+event.type+"] \n"+event.lastEventId+"\n"+JSON.stringify(event.data,null,2)+"</pre>");
        // $("#sse-console").prepend(el);
        // el.scroll()
        terminal.writeln("[ \x1B[1;3;32m"+event.type.toUpperCase()+"\x1B[0m ]");
        terminal.writeln("\x1b[1;30mTIME\x1b[0m : "+moment().format("DD/MM/YYYY HH:mm:ss.SSS"));
        terminal.writeln("\x1b[1;30mUSER\x1b[0m : "+event.lastEventId.split("@")[0]);
        terminal.writeln("\x1b[1;30mGROUP\x1b[0m : "+event.lastEventId.split("@")[1].split("#")[0]);
        terminal.writeln("\x1b[1;30mID\x1b[0m : "+event.lastEventId.split("@")[1].split("#")[1]);
        terminal.writeln("\x1b[1;30mDATA\x1b[0m\n"+JSON.stringify(event.data)+"\n");
        
      }, false);
    } else {
      document.getElementById("sse-console").innerHTML = "Sorry, your browser does not support maya server-sent events...";
    }
  }

});