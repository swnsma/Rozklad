function ScriptManager() {
    var classes = [];
    this.add = function(cl) {
        classes.push(cl);
    };

    this.run = function() {
        $(document).ready(function() {
            try {
                for(var i in classes) {
                    new classes[i];
                }
            } catch (e) {
                console.error('ERROR' + e.message);
            }
        });
    }
}

var manager = new ScriptManager();
manager.run();