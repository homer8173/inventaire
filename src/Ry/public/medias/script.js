/*lancement ajax */

function inventaire(me){
    //console.log("url",me.id);
    var url = jQuery(me).attr('data-url');
    var url_redirection = jQuery(me).attr('data-redirection');
    jQuery("#chargement-inv").show();
    jQuery("#chargement-inv").text('Chargement terminé');

   jQuery
       .ajax({
           url:url,
           type:'GET',
           dataType:'json',
           success: function (data){

               console.log(data);
               if (data.message!='') {
                   alert(data.message) ;
               }
               jQuery("#chargement-inv").text('Chargement terminé');
               window.location.href = url_redirection;
               jQuery("#chargement-inv").hide();
           },
           error: function (){
               jQuery("#chargement-inv").hide();
           }

       });
}

function pmpConstitue(me){
    //console.log("url",me.id);
    jQuery
        .ajax({
            url:me.id,
            type:'GET',
            dataType:'json',
            success: function (data){
                console.log(data);
            },
            error: function (){
                console.log(error);
            }

        });
}