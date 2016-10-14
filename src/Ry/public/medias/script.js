/*lancement ajax */

function inventaire(me){
    //console.log("url",me.id);
   jQuery('#'+me.id).css('display','none');
   jQuery
       .ajax({
           url:me.id,
           type:'GET',
           dataType:'json',
           success: function (data){
               console.log(data);
           },
           errror: function (){
               console.log(error);
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
            errror: function (){
                console.log(error);
            }

        });
}