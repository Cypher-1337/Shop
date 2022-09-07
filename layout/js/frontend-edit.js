// Show password in edit-profile.php
var passField = $('.password');
$('.show-pass').hover(function (){

    passField.attr('type', 'text');

}, function(){


    passField.attr('type', 'password');


});

$('[placeholder]').focus(function (){
    $(this).attr('data-text', $(this).attr('placeholder'));
    $(this).attr('placeholder', '');

}).blur(function () {
    $(this).attr('placeholder', $(this).attr('data-text'));
});

$('.sign-page span').click(function (){
    $(this).addClass("active").siblings().removeClass("active");
    $('.sign-page form').hide();
    $('.' + $(this).data('class')).fadeIn(500);
    
})

$('.live-name').keyup(function (){

    $('.live-preview .caption h3').text($(this).val());
})

$('.live-desc').keyup(function (){

    $('.live-preview .caption p').text($(this).val());
})

$('.live-price').keyup(function (){

    $('.live-preview span').text($(this).val() + '$');
})


