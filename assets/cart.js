jQuery(function($) {
    $('.btnAddProduct').click(function(e) {

        var url = $(e.currentTarget).attr('url');
        $(e.currentTarget).prop("disabled",true);
       
       $.post(url).done(function(ret){
           console.log(ret);
        })
        .fail(function(){
            alert("error");
        })
        .always(function(){
           $(e.currentTarget).prop("disabled",false);
        });
           
    });
});