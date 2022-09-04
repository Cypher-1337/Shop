var passField = $('.password');

$('.show-pass').hover(function (){

    passField.attr('type', 'text');

}, function(){


    passField.attr('type', 'password');


}
);

// Dashboard 

$('.toggle-info').click(function (){

    $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(150);

    if($(this).hasClass('selected')){
        
        $(this).html("<i class='fa fa-minus fa-lg'></i>");
    }else{
        $(this).html("<i class='fa fa-plus fa-lg'></i>");
    }

minus
})


$('.confirm').click(function (){
    return confirm("Are you sure you want to delete this user ?");
})

$('.confirm_cat').click(function (){
    return confirm("Are you sure you want to delete this Category ?");
})


$('.cat-name').click(function (){
    $(this).next('.full-view').fadeToggle(500);
})

$('.option span').click(function (){
    $(this).addClass('active ').siblings('span').removeClass('active');

    if($(this).data('view') === 'full'){
        $('.full-view').fadeIn(200);
    }else{
        $('.full-view').fadeOut(200);
    }
})
