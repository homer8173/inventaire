/*lancement ajax */

function inventaire(me){
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