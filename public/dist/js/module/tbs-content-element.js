$(function () {
    'use strict'

    $(function () {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
    })
    $(function() {
        $('.item').matchHeight();
    });
    $(function () {
        bsCustomFileInput.init();
    });

    $(function () {
        // codeMirrorTsConfig
        CodeMirror.fromTextArea(document.getElementById("codeMirrorTsConfig"), {
            theme: "dracula",
            lineNumbers: true,
            autoRefresh:true,
            showTrailingSpace: true
        });
        // codeMirrorTypoScript
        CodeMirror.fromTextArea(document.getElementById("codeMirrorTypoScript"), {
            theme: "dracula",
            autoRefresh:true,
            lineNumbers: true,
            lineWrapping: true,
        });
        // codeMirrorHtml
        CodeMirror.fromTextArea(document.getElementById("codeMirrorHtml"), {
            mode: "htmlmixed",
            theme: "dracula",
            autoRefresh:true,
            lineNumbers: true,
            lineWrapping: true,
        });
        // codeMirrorTtContent
        CodeMirror.fromTextArea(document.getElementById("codeMirrorTtContent"), {
            mode: "php",
            theme: "dracula",
            autoRefresh:true,
            lineNumbers: true,
            lineWrapping: true,
        });
        // codeMirrorSqlOverride
        CodeMirror.fromTextArea(document.getElementById("codeMirrorSqlOverride"), {
            mode: "sql",
            theme: "dracula",
            autoRefresh:true,
            lineNumbers: true,
            lineWrapping: true,
        });
        // codeMirrorSqlOverride
        CodeMirror.fromTextArea(document.getElementById("codeMirrorSqlNew"), {
            mode: "sql",
            autoRefresh:true,
            lineNumbers: true,
            lineWrapping: true,
            theme: "dracula",
        });
        // codeMirrorHtmlBackend
        CodeMirror.fromTextArea(document.getElementById("codeMirrorHtmlBackend"), {
            mode: "htmlmixed",
            autoRefresh:true,
            lineNumbers: true,
            lineWrapping: true,
            theme: "dracula",
        });
        // codeMirrorLocalLang
        CodeMirror.fromTextArea(document.getElementById("codeMirrorLocalLang"), {
            mode: "xml",
            autoRefresh:true,
            lineNumbers: true,
            lineWrapping: true,
            theme: "dracula",
        });
        // codeMirrorDeLang
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDeLang"), {
            mode: "xml",
            autoRefresh:true,
            lineNumbers: true,
            lineWrapping: true,
            theme: "dracula",
        });
        // codeMirrorTtContentNewCode
        CodeMirror.fromTextArea(document.getElementById("codeMirrorTtContentNewCode"), {
            mode: "php",
            autoRefresh:true,
            lineNumbers: true,
            lineWrapping: true,
            theme: "dracula",
        });


    })
});
