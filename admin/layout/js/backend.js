$(function () {

    'use strict';

    // Function in Dashboard to Hide and Show Latest Items
    
    $('.toggle_info').click(function () {
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(200);

        if($(this).hasClass('selected')){
            $(this).html("<i class='fa fa-minus fa-lg'></i>")
        }else {
            $(this).html("<i class='fa fa-plus fa-lg'></i>")
        }
    })
    
    
    // Function To Remove Placeholder when Focus on Input
    $('[placeholder]').focus(function () {
        // Store Value of Placeholder in [ data-text ]
        $(this).attr("data-text", $(this).attr('placeholder'));

        // Then Remove Placeholder
        $(this).attr('placeholder', '');

    }).blur(function () {
        // Return Value to Placeholder when Blur on Input
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    // Add Astrisc to Inputs

    $('input').each(function () {

        if($(this).attr('required') === 'required'){
            $(this).after('<span class="astrisc">*</span>');
        }
    });

    // Show Password When Hover on Eye Icon
    $('.show-pass').hover(function () {
        $('.password').attr('type', 'text');
    }, function () {
        $('.password').attr('type', 'password');
    });

    // Display Alert to Warning When Presses on Delte Button

    $('.confirm').click(function () {

        return confirm("You Will Remove This User, Are you Sure?!");
    })

    // Category View Option
    $('.cat h3').click(function () {
        $(this).next('.full_view').fadeToggle(200);
    })

    $('.ordering span').click(function () {
        $(this).addClass('active').siblings('span').removeClass('active');

        if($(this).data("view") === 'full'){
            $('.cat .full_view').fadeIn(200);
        }else {
            $('.cat .full_view').fadeOut(200);
        }
    })

});
