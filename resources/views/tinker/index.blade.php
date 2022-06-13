<form action="tinker" id="tinker-form" method="post">
    @method('post')
    @csrf
    <textarea id="aceeditorcode" name="aceeditorcode"></textarea>
    <textarea id="tinkercode" name="tinkercode" hidden></textarea>
    <div class="flex items-center">
        <button type="submit" class="border-red-600 bg-red-400 rounded mt-4 px-4 py-2 text-white">Submit</button>
    </div>
</form>
<div id="tinker_result" class="mt-8"></div>
<script>
    var editor = undefined;
    $(document).ready(function(){
        editor = ace.edit("aceeditorcode",{
            mode: "ace/mode/php",
            selectionStyle:"text",
            useSoftTabs: true,
            tabSize: 2,
            maxLines: Infinity,
            minLines: 30,
            enableBasicAutocompletion: true,
            enableLiveAutocompletion: true,
        });
        editor.setValue("{!! $tinkercode !!}");
    });

    $(document).on('submit', '#tinker-form', function(event) {
        $('#tinkercode').val(editor.getValue());
        $.pjax.submit(event, '#tinker_result', {'Ok': 'sdfsdf'});
    });
</script>