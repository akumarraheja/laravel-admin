<form action="tinker" method="post" targetdiv="#tinker_result" pjax-container>
    @method('post')
    @csrf
    <textarea id="tinkercode" name="tinkercode"></textarea>
    <div class="flex items-center">
        <button type="submit" class="border-red-600 bg-red-400 rounded mt-4 px-4 py-2 text-white">Submit</button>
    </div>
</form>
<div id="tinker_result" class="mt-8"></div>
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