(function (window, $) {
    $(document).ready(function () {

        var epeventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
        var epeventer = window[epeventMethod];
        var epmessageEvent = epeventMethod === "attachEvent" ? "onmessage" : "message";
        // Listen to message from child window
        epeventer(epmessageEvent, function (e) {
            var embedcode = "";
            try {
                if (e.data.indexOf("ygvideoembed") === 0) {
                    embedcode = e.data.split("|")[1];
                    if (embedcode.indexOf("[") !== 0) {
                        embedcode = "<p>" + embedcode + "</p>";
                    }
                    
                    if (window.tinyMCE !== null && window.tinyMCE.activeEditor !== null && !window.tinyMCE.activeEditor.isHidden()) {
                        if (typeof window.tinyMCE.execInstanceCommand !== 'undefined') {
                            window.tinyMCE.execInstanceCommand(
                                    window.tinyMCE.activeEditor.id,
                                    'mceInsertContent',
                                    false,
                                    embedcode);
                        } else {
                            send_to_editor(embedcode);
                        }
                    }
                }
            } catch (e) {

            }
        }, false);
    });
})(window, jQuery);