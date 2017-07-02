$(function () {

    'use strict';

    // Switch between Login and Register
    $('.login-page h1 span').click(function () {

        $(this).addClass('active').siblings().removeClass('active');

        $('.login-page form').hide();
        $('.'+$(this).data('class')).fadeIn();
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


    // Display Alert to Warning When Presses on Delte Button
    $('.confirm').click(function () {

        return confirm("You Will Remove This User, Are you Sure?!");
    })


    // Live Preview when type Details of Item in New-Ads Page

    $('.live-name').keyup(function () {
        $('.live-item .caption h3').text($(this).val());
    })

    $('.live-desc').keyup(function () {
        $('.live-item .caption p').text($(this).val());
    })

    $('.live-price').keyup(function () {
        $('.live-item .price_tag').text($(this).val() + "$");
    })

    // Show number of words in Description in Profile Page

        $(function () {
            $('.desc-specific').each(function(){
                var len=$(this).text().length;
                if(len>80)
                {
                    $(this).text($(this).text().substr(0,60)+'...');
                }
            });
        });

    // Show number of words in Description in Profile Page

    $(function () {
        $('.desc-item').each(function(){
            var len=$(this).text().length;
            if(len>80)
            {
                $(this).text($(this).text().substr(0,60)+'...');
            }
        });
    });




});
