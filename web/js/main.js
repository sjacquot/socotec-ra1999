$(function() {
    //check box sans object
    $('form ins').click(function () {
        var input = $(this).prev();
        var name = input.attr('name');
        if (input.data('info') === null) {
            $('form input[name="' + name + '"]:not([data-info="null"])').each(function () {
                $(this).attr('checked', false);
                $(this).parent().removeClass('checked');
            });
        }else{
            $('form input[name="' + name + '"][data-info="null"]').each(function () {
                $(this).attr('checked', false);
                $(this).parent().removeClass('checked');
            })
        }
    })

    //Picture order
    var pictureOrder = $( "ul[id*=picturesOrder]" );

    pictureOrder.sortable();
    pictureOrder.disableSelection();

    $( "ul[id*=picturesOrder] input" ).attr('checked', true);

    $("input[id*=picturesOrder]").attr('name', 'upload_picture[]');

    $("input[id*=picturesUploaded]").change(function (){
        file = $(this)[0].files;

        for(var i=0; i<file.length; i++)
        {
            $( "ul[id*=picturesOrder]" ).append(
                '<li class="ui-sortable-handle"><div class="checkbox"><label class=""><div class="icheckbox_square-blue checked"><input type="checkbox" id="s5ab7e1fac016b_picturesOrder_1" name="upload_picture[]" value="'+file[i].name+'" style="position: absolute; opacity: 0;" checked="checked"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div><span class="control-label__text">'+file[i].name+'</span></label></div></li>')
        }
    });

    //action on report form
    // $("form[action*=report]").submit(function(e) {
    //     e.preventDefault(); // avoid to execute the actual submit of the form.
    //     getdoc($(this));
    // });

    //action on certificate from
    // $("form[action*=certificate]").submit(function(e) {
    //     e.preventDefault(); // avoid to execute the actual submit of the form.
    //     getdoc($(this));
    // });

    // funciton to get the doc from the report or certificate
    function getdoc(form) {
        var url = form.attr('action'); // the script where you handle the form input.
        var basicUrl = $(location).attr('origin');

        $.ajax({
            type: "POST",
            url: url,
            data: $("form").serialize(), // serializes the form's elements.
            success: function(data)
            {
                window.open(basicUrl+data);
            }
        });

        window.location.replace(basicUrl + '/admin/app/operation/list');
    }


});
