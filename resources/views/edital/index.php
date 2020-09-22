<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Importando o Font Awesome -->
    <script src="https://kit.fontawesome.com/a65264833f.js" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap -->
    <link href = "https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
 
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fredoka+One">
    
    <title></title>
    
</head>

<body>
        <script>
            var instituicao_selecionada = <?php echo $instituicao_selecionada; ?>;
            var ano_selecionado         = <?php echo $ano_selecionado; ?>;
            var tipo_selecionado        = <?php echo $tipo_selecionado; ?>;
            var instituicoes            = <?php echo $instituicoes; ?>; 
            var anos                    = <?php echo $anos; ?>;
            var tipos                   = <?php echo $tipos; ?>;
            var editais                 = <?php echo $editais; ?>;
            var editais_com_anexo       = <?php echo $editais_com_anexo; ?>;
            var anexos                  = <?php echo $anexos; ?>;
        </script>
    <section class="sectionmain" id="view-conteudo">
        <div id="instituicoes">
            <div id="instituicoes_index">
                <script>
                    for(var i=0; i<instituicoes.length; i++){
                        if(instituicoes[i].id == instituicao_selecionada){
                            if(i == (instituicoes.length - 1)){
                                $('#instituicoes_index').append('<a>' + instituicoes[i].nome + '</a>')
                            }
                            else{
                                $('#instituicoes_index').append('<a>' + instituicoes[i].nome + ' | </a>')
                            }
                        }
                        else{
                            if(i == (instituicoes.length - 1)){
                                $('#instituicoes_index').append('<a href="/filtrar/' + instituicoes[i].id + '/' + ano_selecionado + '/' + tipo_selecionado + '" instituicao_attr="' + instituicoes[i].id + '">' + instituicoes[i].nome + '</a>');
                            }
                            else{
                                $('#instituicoes_index').append('<a href="/filtrar/' + instituicoes[i].id + '/' + ano_selecionado + '/' + tipo_selecionado + '" instituicao_attr="' + instituicoes[i].id + '">' + instituicoes[i].nome + '</a> | ');
                            }
                        }
                    }
                </script>
            </div>
        </div>
        <br><em><strong>Recomendamos o uso dos navegadores <span style="color: #ff0000;">Mozilla Firefox <span style="color: #000000;">ou</span> Google Chrome.</span></strong></em>
        <div id="anos">
            <div id="anos_index">
                <script>
                    for(var i=0; i<anos.length; i++){
                        if(anos[i].ano == ano_selecionado){
                            if(i == (anos.length - 1)){
                                $('#anos_index').append(anos[i].ano)
                            }
                            else{
                                $('#anos_index').append(anos[i].ano + ' | ')
                            }
                        }
                        else{
                            if(i == (anos.length - 1)){
                                $('#anos_index').append('<a href="/filtrar/' + instituicao_selecionada + '/' + anos[i].ano + '/' + 1 + '" id= "filtra_ano" value="' + anos[i].ano + '">' + anos[i].ano + '</a>');
                            }
                            else{
                                $('#anos_index').append('<a href="/filtrar/' + instituicao_selecionada + '/' + anos[i].ano + '/' + 1 + '" id= "filtra_ano" value="' + anos[i].ano + '">' + anos[i].ano + '</a> | ');
                            }
                        }
                    }
                </script>
            </div>
        </div>
        <fieldset>
            <legend id="legenda_editais">EDITAIS <script>$('#legenda_editais').append(ano_selecionado)</script></legend>
            <div id="tipos">
                <div id="tipos_index">
                    <script>
                        for(i=0; i < tipos.length; i++){
                            if(tipos[i].id == tipo_selecionado){
                                if(i == (tipos.length - 1)){
                                    $('#tipos_index').append(tipos[i].nome)
                                }
                                else{
                                    $('#tipos_index').append(tipos[i].nome + ' | ')
                                }
                            }
                            else{
                                if(i == (tipos.length - 1)){
                                    $('#tipos_index').append('<a href="/filtrar/' + instituicao_selecionada + '/' + ano_selecionado + '/' + tipos[i].id + '" id= "filtra_tipo" value="' + tipos[i].id + '">' + tipos[i].nome + '</a>')
                                }
                                else{
                                    $('#tipos_index').append('<a href="/filtrar/' + instituicao_selecionada + '/' + ano_selecionado + '/' + tipos[i].id + '" id= "filtra_tipo" value="' + tipos[i].id + '">' + tipos[i].nome + '</a> | ')
                                }
                            }
                        }
                    </script>
                </div>
            </div>
            <div id="editais">
                <div id="editais_index">
                    <script>
                        for(i = 0; i < editais.length; i++){
                            $('#editais_index').append('<a href="#">' + editais[i].nome + '</a><br>')
                            for(j = 0; j < editais_com_anexo.length; j++){
                                if(editais[i].id == editais_com_anexo[j]){
                                    for(k = 0; k < anexos.length; k++){
                                        if(editais[i].id == anexos[k].pai_id){
                                        $('#editais_index').append('<i class="fa fa-arrow-right" aria-hidden="true"></i>') 
                                        $('#editais_index').append('<a href="#">' + anexos[k].nome + '</a><br>') 
                                        }
                                    }
                                }
                            }
                        }
                    </script>
                </div>
            </div>
        </fieldset>
    </section>
</body>

<script>
/*
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('#instituicoes_index a').click(function(event) {
    var filtro = {
        instituicao_id :    $(this).attr('instituicao_attr'),
        ano :               ano_selecionado,
        tipo_id :           tipo_selecionado,
    }

    console.log(filtro)
    
    instituicao_selecionada = $(this).attr('instituicoes_index')
    $.ajax({ 
        url: "/filtrarpost",
        type: "post",
        data: filtro,
        dataType: "json",
        success: function (response) 
        {
            $('#instituicoes_index').remove()
            $('#instituicoes').append('<div id="instituicoes_index">')
            for(var i =0; i<response.instituicoes.length; i++){
                if(response.instituicoes[i].id == instituicao_selecionada){
                    $('#instituicoes_index').append(response.instituicoes[i].nome + ' |')
                }
                else{
                    $('#instituicoes_index').append('<a href="/filtrar/' + instituicoes[i].id + '/' + ano_selecionado + '/' + tipo_selecionado + '">' + response.instituicoes[i].nome + '</a> |');
                }
            }
            $('#instituicoes').append('</div>')
        }
            });

    event.preventDefault();
});
*/
</script>

</html>