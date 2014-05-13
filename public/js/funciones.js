var variableid;

function reply_click(clicked_id)
{
    variableid = clicked_id;
    alert("ID BOTON: "+variableid);
    var wp = window.location;
    base_url=wp.protocol+"//"+wp.host+"/"+wp.pathname.split('/')[1];
    //alert(base_url+"/public/comprobar.php");
    $.ajax({
            url: base_url+"/public/comprobar.php",
            type: "post",
            data: "indice="+variableid,
            success: function(retornado){   //creacion de variable retornado que guarda todo lo que devuelva la consulta ajax(validar.php)
                $("#content").html(retornado);
            }
    });
}