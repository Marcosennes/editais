<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Blade -->
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->

    <!-- Sem Blade -->
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <!-- <input type="hidden" name="_token" value="<?php //echo csrf_token(); ?>"> -->

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
                                $('#instituicoes_index').append('<a href="/filtrar/' + instituicoes[i].id + '/' + ano_selecionado + '/' + tipo_selecionado + '" instituicao_attr="' + instituicoes[i].id + '" ano_attr = "' + ano_selecionado +'" tipo_attr = "' + tipo_selecionado +'">' + instituicoes[i].nome + '</a>');
                            }
                            else{
                                $('#instituicoes_index').append('<a href="/filtrar/' + instituicoes[i].id + '/' + ano_selecionado + '/' + tipo_selecionado + '" instituicao_attr="' + instituicoes[i].id + '" ano_attr = "' + ano_selecionado +'" tipo_attr = "' + tipo_selecionado +'">' + instituicoes[i].nome + '</a> | ');
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
                                        $('#tipos_index').append('<a href="" instituicao_attr="' + instituicao_selecionada + '" ano_attr = "' + ano_selecionado +'" tipo_attr = "' + tipos[i].id +'" value="' + tipos[i].id + '">' + tipos[i].nome + '</a>')
                                    }
                                    else{
                                        $('#tipos_index').append('<a href="" instituicao_attr="' + instituicao_selecionada + '" ano_attr = "' + ano_selecionado +'" tipo_attr = "' + tipos[i].id +'" value="' + tipos[i].id + '">' + tipos[i].nome + '</a> | ')
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
        <!-- Formulário de testes -->
        <!-- 
        <form action="/filtrarpost" method="POST"> 
            <?php echo csrf_field(); ?>                    
            <input type="text" name="instituicao_id">
            <input type="text" name="ano">
            <input type="text" name="tipo_id">
            <button type="submit">Enviar</button>
        </form>
        -->   
    </body>
<script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
    document.querySelectorAll('#tipos_index a').forEach(function(link) {
        
        // $('#ocultaLancamento').on('click', function()
        // $(link).on('click', function(event))
        // link.onclick = function(event)
        $(link).on('click', function(event){
            event.preventDefault();

        var filtro = {
            instituicao_id :    $(this).attr('instituicao_attr'),
            ano :               $(this).attr('ano_attr'),
            tipo_id :           $(this).attr('tipo_attr'),
        }

        console.log(filtro)
        
        tipo_selecionado = $(this).attr('tipo_attr')

        $.ajax({ 
            url: "/filtrarpost",
            type: "post",
            data: filtro,
            dataType: "json",
            success: function (response) 
            {
                console.log(response)
                tipos_update(response)
                editais_update(response)

            }
            
        })
    })
})
</script>
<script>
    /*
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
    document.querySelectorAll('#instituicoes_index a').forEach(function(link) {
        link.onclick = function(event){
            event.preventDefault();

        var filtro = {
            instituicao_id :    $(this).attr('instituicao_attr'),
            ano :               $(this).attr('ano_attr'),
            tipo_id :           $(this).attr('tipo_attr'),
        }

        console.log(filtro)
        
        instituicao_selecionada = $(this).attr('instituicao_attr')
        $.ajax({ 
            url: "/filtrarpost",
            type: "post",
            data: filtro,
            dataType: "json",
            success: function (response) 
            {
                console.log(response)
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
            
        })
    }
})
*/
</script>
<script>
    /*
    document.querySelectorAll('#instituicoes_index a').forEach(link => {
        link.onclick = function(event){
            event.preventDefault()
            console.log('aaaa')
        }
    })
    */
</script>
<script>
    function editais_update(response){
        $('#editais_index').remove()
        $('#editais').append('<div id="editais_index">')
        for(i = 0; i < response.editais.length; i++){
            $('#editais_index').append('<a href="#">' + response.editais[i].nome + '</a><br>')
            for(j = 0; j < response.editais_com_anexo.length; j++){
                if(response.editais[i].id == response.editais_com_anexo[j]){
                    for(k = 0; k < response.anexos.length; k++){
                        if(response.editais[i].id == response.anexos[k].pai_id){
                        $('#editais_index').append('<i class="fa fa-arrow-right" aria-hidden="true"></i>') 
                        $('#editais_index').append('<a href="#">' + response.anexos[k].nome + '</a><br>') 
                        }
                    }
                }
            }
        }
        $('#editais').append('</div>')
    }
</script>

<script>
    function tipos_update(response){
        $('#tipos_index').remove()
        $('#tipos').append('<div id="tipos_index">')
        for(i=0; i < response.tipos.length; i++){
            if(response.tipos[i].id == response.tipo_selecionado){
                if(i == (response.tipos.length - 1)){
                    $('#tipos_index').append(response.tipos[i].nome)
                }
                else{
                    $('#tipos_index').append(response.tipos[i].nome + ' | ')
                }
            }
            else{
                if(i == (response.tipos.length - 1)){
                    $('#tipos_index').append('<a href="" instituicao_attr="' + response.instituicao_selecionada + '" ano_attr = "' + response.ano_selecionado +'" tipo_attr = "' + response.tipos[i].id +'" value="' + response.tipos[i].id + '">' + response.tipos[i].nome + '</a>')
                }
                else{
                    $('#tipos_index').append('<a href="" instituicao_attr="' + response.instituicao_selecionada + '" ano_attr = "' + response.ano_selecionado +'" tipo_attr = "' + response.tipos[i].id +'" value="' + response.tipos[i].id + '">' + response.tipos[i].nome + '</a> | ')
                }
            }
        }
        tipo_selecionado = response.tipo_selecionado
        $('#tipos').append('</div>')
        
    }
</script>
</html>