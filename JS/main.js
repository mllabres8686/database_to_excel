/**
 * Created by miguel.llabres on 09/03/2017.
 */


$(document).ready(function() {
    //AL PULSAR CONECT
    $(document).on('click',"#butt_connection",function(e){
        e.preventDefault();
        $("#connection_result").show();
        var host = $("#host").val();
        var user = $("#user").val();
        var pass = $("#pass").val();
        var port = $("#port").val();
        var error = "";

        if(!host){
            console.log("no host");
            error += " host";
        }
        if(!user){
            console.log("no user");
            error += " user";
        }
		/*
        if(!pass){
            console.log("no pasword");
            error += " password";
        }
		*/
        if(!port){
            console.log("no port");
            error += " port";
        }
        if(error.length == 0){
            console.log("connecting to "+host+":"+port+" - user:"+user+"password:"+pass);
            $("#connection_result").text("CONNECTING TO ["+host+":"+port+"] - [USER]:"+user+", [PASSWORD]: YES");


            //TODO codigo de extraccion de BBDD
            var url = "PHP/connect.php";
            $.post(url, {
                host: host,
                user:user,
                password:pass,
                port:port
            }, function (data, status, xhr) {

            }).done(function (data) {
                if(data !== "Conexión establecida"){
                    $("#connection_result").text( "Access not valid!" ).fadeOut( 3000 );
                    alert(data);
                }else{
                    $("#connection_result").text( "CONECTED!" );
                    $("#refreshable_content").load("index.php #refreshable_content", function(){
                        console.log('databases section reloaded');
                    });
                }
            }).fail(function () {
                console.log('fail');
            }).always(function (data) {
                console.log('finished');
            });
        }else{
            alert("introduce "+error);
        }


    });

    //AL SELECCIONAR UNA BASE DE DATOS DE LA LISTA
    $(document).on('change',"#ddbb",function(e){
        console.log("Base de datos seleccionada");

        var database =$(this).val();
        //TODO codigo de extraccion de tablas
        var url = "PHP/get_tables.php";
        $.post(url, {
            database: database
        }, function (data, status, xhr) {

        }).done(function (data) {
            if(data !== "Tablas recuperadas"){

                alert(data);
            }else{
                $("#refreshable_content").load("index.php #refreshable_content", function(){
                    console.log('tables section reloaded');
                });
            }

        }).fail(function () {
            console.log('fail');
        }).always(function (data) {
            console.log('finished');
        });


        $("#tables_section").show();
    });


    //AL HABER SELECCIONADO TABLS Y VISTAS
    $(document).on('click',"#butt_tables",function(e){
        e.preventDefault();
        console.log("Tablas y vistas de datos seleccionadas");
        var array_tablas = [];
        var array_tablas_tostring;

        $( ":checkbox:checked" ).each(function (index , name) {
            console.log($(this).val());
            array_tablas.push($(this).val());
        });

        array_tablas_tostring = JSON.stringify(array_tablas);

        //TODO codigo de extraccion de tablas, generacion del objeto PHPExcel


    });


    //AL HABER RELLENADO LOS DETALLES DEL ARCHIVO EXCEL
    $(document).on('click',"#butt_db2excel", function(e){
        e.preventDefault();
        console.log("Descarga del fichero");
        var array_tablas = [];
        var array_tablas_tostring;
        var author = $("#doc_author").val();
        var title = $("#doc_name").val();
        var category = $("#doc_category").val();
        var description = $("#doc_description").val();


        $( ":checkbox:checked" ).each(function (index , name) {
            array_tablas.push($(this).val());
        });

        array_tablas_tostring = JSON.stringify(array_tablas);

        //TODO codigo de extraccion de tablas, generacion del objeto PHPExcel
        var url = "PHP/session_variable.php";
        $.post(url, {
            author:author,
            title:title,
            description:description,
            category:category,
            tables_array:array_tablas_tostring
        }, function (data, status, xhr) {
            console.log(data);
            console.log(status);
            console.log(xhr);

        }).done(function (data) {
           //alert(data);
            $("#refreshing_excel").load("index.php #refreshing_excel", function(){
                console.log('excel created');
				console.log(data);
            });
        }).fail(function () {
            console.log('fail');
        }).always(function (data) {
            console.log('finished');
        });
    });
});