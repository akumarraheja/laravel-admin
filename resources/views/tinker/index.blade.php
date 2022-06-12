<textarea id="tinkercode"></textarea>
<script>
    $(document).ready(function(){
        var editor = CodeMirror.fromTextArea($('#tinkercode').get(0), {
            mode: 'php',
            lineNumbers: true,
            indentUnit: 2,
            tabSize: 2,
            lineWiseCopyCut: true,
            autoCloseBrackets: true,
            matchBrackets: true,
            scanUp: true,
            minFoldSize: 3,
            gutters: ['CodeMirror-foldgutter'],
            foldGutter: true,
            highlightSelectionMatches: {
                minChars: 2,
                style: 'akr-highlight'
            }
        });
        editor.getDoc().setValue("<?php\n\n\n\n\n\n\n\n\n\n\n\n\n");
        editor.setOption("extraKeys", {
            'Ctrl-/': function(cm) {
                cm.toggleComment();
            }
        });
        // editor.setOption('extraKeys', {"Ctrl-Space": function(){
        //     var options = {
        //         hint: function() {
        //             return {
        //                 from: editor.getDoc().getCursor(),
        //                 to: editor.getDoc().getCursor()
        //             }
        //         },
        //         pick: function(completion){
        //             console.log(completion);
        //         }
        //     };
        //     editor.showHint(options);
        // }});
    });
</script>