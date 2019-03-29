$(function (){
    'use strict'

    // Trigger The Selectboxit

	$("select").selectBoxIt({

		autoWidth: false

	});

    $('[placeholder]').focus(function() {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function (){
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    //- Add Astrisk on Required Field
    $('input').each(function (){
        if ($(this).attr('required') === 'required') {
            $(this).attr('<span class="asterisk">*</span>');
        }
    });

    var passFaild = $('.password');

    $('.show-pass').hover(function () {

        passFaild.attr('type', 'text');

    }, function () {

        passFaild.attr('type', 'password');

    });

    // confirm message
    $(".confirm").click(function () {
        return confirm("Are you Sure?");
    });
});