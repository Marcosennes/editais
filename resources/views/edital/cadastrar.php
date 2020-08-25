<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token requisição AJAX -->
    <!-- <meta name="csrf-token" content="<?php //echo csrf_field(); ?>"> -->

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
    <section class="sectionmain" id="view-conteudo">
        <script>
            var instituicoes            = <?php echo $instituicoes; ?>; 
            var anos                    = <?php echo $anos; ?>;
            <?php $array_js = json_encode($anos_tipos_instituicoes); echo "var anos_tipos_instituicoes = " . $array_js . ";\n"; ?>
            var tipos                   = <?php echo $tipos; ?>;
        </script>
<div class="offset-lg-2 col-12 col-md-12 col-lg-8" style="background-color: white">
    <div class="container">
        <div class="offset-2 col-8">
            <div class="row d-flex flex-column">
                <form method="post" action="/salva_edital" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>                    <h3 style="margin-top: 20px;">Cadastrar edital</h3>
                    <label for="instituicao">Instituição</label>
                        <select id = "instituicoes_select" name="instituicao_id" class="form-control">
                            <script>
                            for(i=0; i < instituicoes.length; i++){
                                $('#instituicoes_select').append('<option value="' + instituicoes[i].id + '">' + instituicoes[i].nome + '</option>')                                
                            }
                            </script>         
                        </select>
                    <label style="margin-top: 10px;" for="edital">Nome do edital</label>
                        <input type="text" class="form-control" name="nome">
                    <label style="margin-top: 10px;" for="arquivo">Selecione o arquivo</label>
                        <input type="file" name="arquivo" id="arquivo">
                        <br>
                    <label style="margin-top: 10px;" for="tipo">Tipo</label>
                        <select id="tipos_select" name="tipo_id" class="form-control">
                            <script>
                                for(i=0; i < tipos.length; i++){
                                    $('#tipos_select').append('<option value="' + tipos[i].id + '">' + tipos[i].nome + '</option>')                                
                                }
                            </script>                 
                        </select>
                    <label style="margin-top: 10px;" for="ano">Ano</label>
                        <input type="text" onkeypress="return onlynumber();" class="form-control" name="ano">
                    <!--
                    <label for="anexo">O edital possui anexo?</label>
                    <a id="adicionar_anexo" class="btn btn-1">Anexar arquivo neste edital</a>
                    -->
                    <button class="btn btn-primary" style="width: 100%; margin-top: 11px;" type="submit">Cadastrar</button>
                </form>
                <?php 
                        session_start();
                        if(isset($_SESSION['cadastro'])){
                            if($_SESSION['cadastro']['validacao'] == true){
                                echo '<div class="alert alert-success" style="margin-top : 20px;" role="alert">' . $_SESSION['cadastro']['mensagem'] . '</div>';
                            }
                            elseif($_SESSION['cadastro']['validacao'] == false){
                                echo '<div class="alert alert-danger" style="margin-top : 20px;" role="alert">' . $_SESSION['cadastro']['mensagem'] . '</div>';
                            }

                            unset($_SESSION['cadastro']);
                        }
                ?> 
                <h3 style="margin-top: 40px;">Cadastrar anexo</h3>
                <form name="formFiltraAnexo">
                    <?php echo csrf_field(); ?>
                    <label for="instituicao">Instituição</label>
                    <select id="instituicoes_anexo_select" name="instituicao_id" class="form-control">
                        <script>
                            for(i=0; i < instituicoes.length; i++){
                                $('#instituicoes_anexo_select').append('<option value="' + instituicoes[i].id + '">' + instituicoes[i].nome + '</option>')                                
                            }
                        </script>                
                    </select>
                    <label for="ano">Ano</label>
                    <select id="ano_anexo_select" name="ano" id="anexoAno" class="form-control">
                        <script>
                            anos_instituicao_selecionada = anos_tipos_instituicoes[0];
                            for(var i=0; i<anos_instituicao_selecionada.length; i++){
                               $("#ano_anexo_select").append('<option value="' + anos_instituicao_selecionada[i].ano + '">' + anos_instituicao_selecionada[i].ano + '</option>')
                            }
                        </script>  
                    </select>
                    <label for="tipo">Tipo</label>
                    <select id="tipos_anexo_select" name="tipo" id="anexoTipo" class="form-control">
                        <script>
                            for(i=0; i < tipos.length; i++){
                                $('#tipos_anexo_select').append('<option value="' + tipos[i].id + '">' + tipos[i].nome + '</option>')                                
                            }
                        </script>  
                    </select>
                    <button class="btn btn-primary" style="width: 100%; margin-top: 11px; margin-bottom: 20px;" type="submit">Filtrar</button>
                </form>
                <form method="post" action="/salva_edital_anexo" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="d-none" id="anexoTable">
                        <div class="table-overflow" style="margin-top: 20px; max-height:400px; overflow-y:auto;">
                            <table id="tableAnexos" class="table table-sm table-striped table-bordered table-hover" style="background-color: white">
                                <thead>
                                    <tr>
                                        <th scope="col">Nome do edital</th>
                                        <th scope="col">Ano</th>
                                        <th scope="col">Selecione</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyTable">
                                </tbody>
                            </table>
                        </div>
                        <label style="margin-top: 10px;" for="edital">Nome do anexo</label>
                            <input type="text" class="form-control" name="nome">
                        <label style="margin-top: 10px;" for="arquivo">Selecione o arquivo</label>
                            <input type="file" name="arquivo" id="arquivo">    
                            <br>
                        <button class="btn btn-primary" style="width: 100%; margin-top: 11px; margin-bottom: 20px;" type="submit">Cadastrar</button>
                    </div>
                </form>
            <?php 
                if(isset($_SESSION['cadastro_anexo'])){
                    if($_SESSION['cadastro_anexo']['validacao'] == true){
                        echo '<div class="alert alert-success" style="margin-top : 20px;" role="alert">' . $_SESSION['cadastro_anexo']['mensagem'] . '</div>';
                    }
                    elseif($_SESSION['cadastro_anexo']['validacao'] == false){
                        echo '<div class="alert alert-danger" style="margin-top : 20px;" role="alert">' . $_SESSION['cadastro_anexo']['mensagem'] . '</div>';
                    }

                    unset($_SESSION['cadastro_anexo']);
                }
            ?> 
            </div>
        </div>
    </div>
</div>
    </section>
</body>

<script>

function onlynumber(evt) {
   var theEvent = evt || window.event;
   var key = theEvent.keyCode || theEvent.which;
   key = String.fromCharCode( key );
   //var regex = /^[0-9.,]+$/;
   var regex = /^[0-9.]+$/;
   if( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
   }
}
</script>

<script type="text/javascript">
        $('form[name="formFiltraAnexo"]').submit(function(event){
            event.preventDefault();

            /*
            var instituicao = $(this).find('#anexoInstituicao').val();
            var ano         = $(this).find('#anexoAno').val();
            var tipo        = $(this).find('#anexoTipo').val();
            */

            $.ajax({
                url: "/filtrarAnexo",
                type: "post",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                
                    $('#anexoTable').removeClass('d-none');
                    $('#bodyTable').remove();
                    $('#tableAnexos').append('<tbody id = "bodyTable"></tbody>');
                    $.each(response, function(index, edital) {
                        $('#bodyTable').append('<tr><td scope="row">' + edital.nome + '</td><td>' + edital.ano + '</td><td><input type="radio" name="pai_id" value="' + edital.id + '"><br></td></tr>');
                    });
                }
            });

        });
</script>
<script>
    $('#instituicoes_anexo_select').click(function(event){

        $("#instituicoes_anexo_select option:selected").each(function() {
            var instituicao_selecionada = $(this).val();
            anos = anos_tipos_instituicoes[(instituicao_selecionada - 1)];
        });
        
        $("#ano_anexo_select option").remove();
        for(var i=0; i<anos.length; i++){
            $("#ano_anexo_select").append('<option value="' + anos[i].ano + '">' + anos[i].ano + '</option>')
        }
    });

    $('#ano_anexo_select').click(function(event){
        $("#instituicoes_anexo_select option:selected").each(function() {

            var instituicao_selecionada = $(this).val();

            $("#ano_anexo_select option:selected").each(function() {
                var ano_selecionado = $(this).val();
                var tipos_ano = [];
                for(i=0; i<anos_tipos_instituicoes[(instituicao_selecionada - 1)].length; i++){
                    if(anos_tipos_instituicoes[(instituicao_selecionada - 1)][i].ano == ano_selecionado){
                        var tamanho = anos_tipos_instituicoes[(instituicao_selecionada - 1)][i].tipos.length
                        for(k=0; k< tamanho; k++){
                            tipos_ano[k] = anos_tipos_instituicoes[(instituicao_selecionada - 1)][i].tipos[k];
                        }
                    }
                }
            
                $("#tipos_anexo_select option").remove();
                for(var i=0; i<tipos_ano.length; i++){
                    $("#tipos_anexo_select").append('<option value="' + tipos_ano[i].tipo_id + '">' + tipos_ano[i].nome + '</option>')
                }
            });
        });
    });
</script>
</html>