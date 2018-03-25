$(function() {
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
});