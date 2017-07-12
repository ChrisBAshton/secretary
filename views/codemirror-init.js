(function () {
    jQuery(document).ready(function () {
        var textarea = document.getElementById("secretary-config-yaml");
        CodeMirror.fromTextArea(textarea, {
            mode: "yaml",
            lineNumbers: true
        });
    });
})();
