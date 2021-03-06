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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fredoka+One">
    <link rel="stylesheet" type="text/css" href="css/stylesheet_cadastrar.css">

    <title></title>

</head>

<body>
    <script>
    var instituicoes = <?php echo $instituicoes; ?>;
    var anos = <?php echo $anos; ?>;
    <?php $array_js = json_encode($anos_tipos_instituicoes); echo "var anos_tipos_instituicoes = " . $array_js . ";\n"; ?>
    var tipos = <?php echo $tipos; ?>;
    var anexos_excluidos = <?php echo $anexos_excluidos; ?>;
    var permissao_array = <?php echo $permissao; ?>;
    var permissao = permissao_array[0].permission;
    </script>
    <header id="header-cadastrar" class="col-12">
        <nav id="navbar-header" class="navbar navbar-expand">
            <div class="row container-fluid" style="margin-left: 10px;">
                <div id="fundo-logo-prefeitura" class="text-center">
                    <figure>
                        <img id="logo-prefeitura" class="img-responsive" src="images/logo-prefeitura.png"
                            alt="Prefeitura de Maricá" title="Prefeitura de Maricá" width="137;" height="39;">
                    </figure>
                </div>
                <div id="navbar-list" class="collapse navbar-collapse container-fluid" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a id="editais-a" class="nav-item nav-link active" href="/cadastrar">Editais</a>
                        <a id="editais-anexo-a" class="nav-item nav-link not-active" href="/cadastrar_anexo">Anexos</a>
                        <script>
                        if (permissao == "admin") {
                            $('.navbar-nav').append(
                                '<a id="novo-usuario" class="nav-item nav-link" href="">Registrar Usuário</a>'
                            )
                        }
                        </script>
                    </div>
                    <a class="nav-item nav-link" href="/logout">
                        <i class="fa fa-sign-out" aria-hidden="true" title="Sair"></i>
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <div id="div-principal-anexo" class="offset-md-2 col-12 col-md-8">
        <section id="section-anexo" class="sectionmain" id="view-conteudo">
            <?php session_start(); ?>
            <div id="container-anexo" class="container">
                <div id="coluna-principal" class="row d-flex flex-column">
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
                    
                        if(isset($_SESSION['exclusao_anexo'])){
                            if($_SESSION['exclusao_anexo']['validacao'] == true){
                                echo '<div class="alert alert-success" style="margin-top : 20px;" role="alert">' . $_SESSION['exclusao_anexo']['mensagem'] . '</div>';
                            }
                            elseif($_SESSION['exclusao_anexo']['validacao'] == false){
                                echo '<div class="alert alert-danger" style="margin-top : 20px;" role="alert">' . $_SESSION['exclusao_anexo']['mensagem'] . '</div>';
                            }
                            
                            unset($_SESSION['exclusao_anexo']);
                        }
                        if(isset($_SESSION['restauracao_anexo'])){
                            if($_SESSION['restauracao_anexo']['validacao'] == true){
                                echo '<div class="alert alert-success" style="margin-top : 20px;" role="alert">' . $_SESSION['restauracao_anexo']['mensagem'] . '</div>';
                            }
                            elseif($_SESSION['restauracao_anexo']['validacao'] == false){
                                echo '<div class="alert alert-danger" style="margin-top : 20px;" role="alert">' . $_SESSION['restauracao_anexo']['mensagem'] . '</div>';
                            }
                            
                            unset($_SESSION['restauracao_anexo']);
                        }
                    ?>
                    <div id="card">
                        <div id="conteudo-card" class="offset-md-1 col-md-10">
                            <div id="card-content" class="card text-center">
                                <div id="card-header-edital" class="card-header">
                                    <ul class="nav nav-tabs card-header-tabs">
                                        <li class="nav-item">
                                            <a id="cadastrar-aba" class="nav-link active" href="#">Cadastrar</a>
                                        </li>
                                        <li class="nav-item">
                                            <a id="excluir-aba" class="nav-link" href="#">Excluir</a>
                                        </li>
                                        <li class="nav-item">
                                            <a id="lixeira-aba" class="nav-link" href="#">Lixeira</a>
                                        </li>
                                    </ul>
                                </div>
                                <div id="card-header-usuario">
                                    <h1>Registrar novo usuário</h1>
                                </div>
                                <div id="cadastrar-body" class="card-body">
                                    <form name="formFiltraEdital">
                                        <?php echo csrf_field(); ?>
                                        <h1>Cadastrar anexo</h1>
                                        <label for="instituicao">Instituição</label>
                                        <select id="instituicoes_anexo_select" name="instituicao_id"
                                            class="form-control">
                                            <script>
                                            for (i = 0; i < instituicoes.length; i++) {
                                                $('#instituicoes_anexo_select').append('<option value="' + instituicoes[
                                                        i].id + '">' +
                                                    instituicoes[i].nome + '</option>')
                                            }
                                            </script>
                                        </select>
                                        <label for="ano">Ano</label>
                                        <select id="ano_anexo_select" name="ano" class="form-control">
                                            <script>
                                            anos_instituicao_selecionada = anos_tipos_instituicoes[0];
                                            for (var i = 0; i < anos_instituicao_selecionada.length; i++) {
                                                $("#ano_anexo_select").append('<option value="' +
                                                    anos_instituicao_selecionada[i].ano +
                                                    '">' + anos_instituicao_selecionada[i].ano + '</option>')
                                            }
                                            </script>
                                        </select>
                                        <label for="tipo">Tipo</label>
                                        <select id="tipos_anexo_select" name="tipo" class="form-control">
                                            <script>
                                            for (i = 0; i < tipos.length; i++) {
                                                $('#tipos_anexo_select').append('<option value="' + tipos[i].id + '">' +
                                                    tipos[i].nome +
                                                    '</option>')
                                            }
                                            </script>
                                        </select>
                                        <button class="btn btn-primary"
                                            style="width: 100%; margin-top: 11px; margin-bottom: 20px;"
                                            type="submit">Filtrar</button>
                                    </form>
                                    <form method="post" action="/salva_edital_anexo" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="d-none" id="editalTable">
                                            <div class="table-overflow"
                                                style="margin-top: 20px; max-height:400px; overflow-y:auto;">
                                                <div class="tableFixHead">
                                                    <table id="tableEdital"
                                                        class="table table-sm table-striped table-bordered table-hover"
                                                        style="background-color: white">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Nome do edital</th>
                                                                <th scope="col">Ano</th>
                                                                <th scope="col">Selecione</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="bodyEditalTable">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <label style="margin-top: 10px;" for="edital">Nome do anexo</label>
                                            <input type="text" class="form-control" name="nome">
                                            <label style="margin-top: 10px;" for="arquivo">Selecione o arquivo</label>
                                            <input type="file" name="arquivo" id="arquivo">
                                            <br>
                                            <button class="btn btn-primary"
                                                style="width: 100%; margin-top: 11px; margin-bottom: 20px;"
                                                type="submit">Cadastrar</button>
                                        </div>
                                    </form>
                                </div>
                                <div id="excluir-body" class="card-body" style="display: none;">
                                    <form name="formFiltraAnexo">
                                        <?php echo csrf_field(); ?>
                                        <h1>Excluir anexo</h1>
                                        <label for="instituicao">Instituição</label>
                                        <select id="instituicoes_edital_select" name="instituicao_id"
                                            class="form-control">
                                            <script>
                                            for (i = 0; i < instituicoes.length; i++) {
                                                $('#instituicoes_edital_select').append('<option value="' +
                                                    instituicoes[
                                                        i].id + '">' +
                                                    instituicoes[i].nome + '</option>')
                                            }
                                            </script>
                                        </select>
                                        <label for="ano">Ano</label>
                                        <select id="ano_edital_select" name="ano" class="form-control">
                                            <script>
                                            anos_instituicao_selecionada = anos_tipos_instituicoes[0];
                                            for (var i = 0; i < anos_instituicao_selecionada.length; i++) {
                                                $("#ano_edital_select").append('<option value="' +
                                                    anos_instituicao_selecionada[i].ano +
                                                    '">' + anos_instituicao_selecionada[i].ano + '</option>')
                                            }
                                            </script>
                                        </select>
                                        <label for="tipo">Tipo</label>
                                        <select id="tipos_edital_select" name="tipo" class="form-control">
                                            <script>
                                            for (i = 0; i < tipos.length; i++) {
                                                $('#tipos_edital_select').append('<option value="' + tipos[i].id +
                                                    '">' +
                                                    tipos[i].nome +
                                                    '</option>')
                                            }
                                            </script>
                                        </select>
                                        <button class="btn btn-primary"
                                            style="width: 100%; margin-top: 11px; margin-bottom: 20px;"
                                            type="submit">Filtrar</button>
                                    </form>
                                    <form method="post" action="/exclui_anexo">
                                        <?php echo csrf_field(); ?>
                                        <div class="d-none" id="anexoTable">
                                            <div class="table-overflow"
                                                style="margin-top: 20px; max-height:400px; overflow-y:auto;">
                                                <div class="tableFixHead">
                                                    <table id="tableAnexos"
                                                        class="table table-sm table-striped table-bordered table-hover"
                                                        style="background-color: white">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Nome do anexo</th>
                                                                <th scope="col">Nome do Edital Pai</th>
                                                                <th scope="col">Ano</th>
                                                                <th scope="col">Selecione</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="bodyTable">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <button class="btn btn-danger"
                                                style="width: 100%; margin-top: 25px; margin-bottom: 20px;"
                                                type="submit">Excluir</button>
                                        </div>
                                    </form>
                                </div>
                                <div id="lixeira-body" class="card-body" style="display: none;">
                                    <h1>Restaurar anexo</h1>
                                    <form method="post" action="/restaura_anexo">
                                        <?php echo csrf_field(); ?>
                                        <div class="table-overflow"
                                            style="margin-top: 20px; max-height:400px; overflow-y:auto;">
                                            <div class="tableFixHead">
                                                <table id="tableEdital"
                                                    class="table table-sm table-striped table-bordered table-hover"
                                                    style="background-color: white">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Nome do anexo</th>
                                                            <th scope="col">Nome do Pai</th>
                                                            <th scope="col">Ano</th>
                                                            <th scope="col">Data de exclusão</th>
                                                            <th scope="col">Selecione</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="trashAnexoTable">
                                                        <script>
                                                        let data_de_exclusao
                                                        $.each(anexos_excluidos, function(index, anexo) {
                                                            data_de_exclusao = anexo.deleted_at.split("T")
                                                            data_de_exclusao[0] = data_de_exclusao[0].split("-")
                                                            $('#trashAnexoTable').append(
                                                                '<tr><td scope="row">' + anexo.nome +
                                                                '</td><td scope="row">' + anexo.nome_pai +
                                                                '</td><td>' +
                                                                anexo.ano + '</td><td> ' + data_de_exclusao[
                                                                    0][2] + '/' + data_de_exclusao[0][1] +
                                                                '/' + data_de_exclusao[0][0] +
                                                                ' </td><td><input type="radio" name="id" value="' +
                                                                anexo.id + '"><br></td></tr>');
                                                        });
                                                        </script>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <button class="btn btn-info"
                                            style="width: 100%; margin-top: 25px; margin-bottom: 20px;"
                                            type="submit">Restaurar</button>
                                    </form>
                                </div>
                                <div id="registrar-usuario">
                                    <form id="registrar-form" action="/registrar" method="post">
                                        <?php echo csrf_field(); ?>
                                        <label for="name">Nome:</label>
                                        <input id="nome-input" type="text" class="form-control" name="name">
                                        <div style="display: none" id="nome_cadastro_vazio">
                                            <span style="color: red;">Preencha o nome</span>
                                        </div>
                                        <label for="cpf">CPF:</label>
                                        <input id="cpf-input" type="text" onkeypress="return onlynumber();"
                                            class="form-control" name="cpf" maxlength="11">
                                        <div style="display: none" id="cpf_cadastro_vazio">
                                            <span style="color: red;">Preencha o CPF</span>
                                        </div>
                                        <label for="email">Email:</label>
                                        <input id="email-input" type="email" class="form-control" name="email">
                                        <div style="display: none" id="email_cadastro_vazio">
                                            <span style="color: red;">Preencha o E-mail</span>
                                        </div>
                                        <label for="password">Senha:</label>
                                        <input id="senha-input" type="password" class="form-control" name="password">
                                        <label for="confirmed-password">Confirme sua
                                            Senha:</label>
                                        <input id="confirma-senha-input" type="password" style="margin-bottom: 20px;"
                                            class="form-control" name="confirmed-password">
                                        <div style="display: none" id="password_cadastro_vazia">
                                            <span style="color: red;">A senha deve possuir pelo menos 5
                                                caracteres</span>
                                        </div>
                                        <div style="display: none" id="password_confirm_cadastro_vazia">
                                            <span style="color: red;">Preencha a senha</span>
                                        </div>
                                        <div style="display: none" id="senhas_diferentes">
                                            <span style="color: red;">As senhas informadas não correspondem</span>
                                        </div>
                                        <div id="admin-button">
                                            <label for="permission">Usuário
                                                administrador?</label>
                                            <select name="permission" id="permissao">
                                                <option value="admin">Sim</option>
                                                <option value="user" selected="selected">Não</option>
                                            </select>
                                        </div>
                                        <br>
                                        <button class="btn btn-info"
                                            style="width: 100%; margin-top: 25px; margin-bottom: 20px;"
                                            onclick="return validaFormRegistrar()" type="submit">Registrar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
<script type="text/javascript">
$('form[name="formFiltraAnexo"]').submit(function(event) {
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
        success: function(response) {
            $('#anexoTable').removeClass('d-none');
            $('#bodyTable').remove();
            $('#tableAnexos').append('<tbody id = "bodyTable"></tbody>');
            console.log(response)
            $.each(response, function(index, anexo) {
                $('#bodyTable').append('<tr><td scope="row">' + anexo.nome +
                    '</td><td scope="row">' + anexo.nome_pai + '</td><td>' +
                    anexo.ano + '</td><td><input type="radio" name="id" value="' +
                    anexo.id + '"></td><br></tr>');
            });
        },
        error: function(e) {
            alert(e)
        }
    });

});
</script>

<script type="text/javascript">
$('form[name="formFiltraEdital"]').submit(function(event) {
    event.preventDefault();

    /*
    var instituicao = $(this).find('#anexoInstituicao').val();
    var ano         = $(this).find('#anexoAno').val();
    var tipo        = $(this).find('#anexoTipo').val();
    */

    $.ajax({
        url: "/filtrarEdital",
        type: "post",
        data: $(this).serialize(),
        dataType: "json",
        success: function(response) {
            $('#editalTable').removeClass('d-none');
            $('#bodyEditalTable').remove();
            $('#tableEdital').append('<tbody id = "bodyEditalTable"></tbody>');
            $.each(response, function(index, edital) {
                $('#bodyEditalTable').append('<tr><td scope="row">' + edital.nome +
                    '</td><td>' +
                    edital.ano + '</td><td><input type="radio" name="pai_id" value="' +
                    edital.id + '"></td><br></tr>');
            });
        }
    });

});
</script>

<!-- mostra nos selects apenas os dados referentes à instituição e/ou ano selecionados -->
<script>
$('#instituicoes_anexo_select').click(function(event) {

    $("#instituicoes_anexo_select option:selected").each(function() {
        var instituicao_selecionada = $(this).val();
        anos = anos_tipos_instituicoes[(instituicao_selecionada - 1)];
    });

    $("#ano_anexo_select option").remove();
    for (var i = 0; i < anos.length; i++) {
        $("#ano_anexo_select").append('<option value="' + anos[i].ano + '">' + anos[i].ano + '</option>')
    }
});

$('#ano_anexo_select').click(function(event) {
    $("#instituicoes_anexo_select option:selected").each(function() {

        var instituicao_selecionada = $(this).val();

        $("#ano_anexo_select option:selected").each(function() {
            var ano_selecionado = $(this).val();
            var tipos_ano = [];
            for (i = 0; i < anos_tipos_instituicoes[(instituicao_selecionada - 1)]
                .length; i++) {
                if (anos_tipos_instituicoes[(instituicao_selecionada - 1)][i].ano ==
                    ano_selecionado) {
                    var tamanho = anos_tipos_instituicoes[(instituicao_selecionada - 1)][i]
                        .tipos.length
                    for (k = 0; k < tamanho; k++) {
                        tipos_ano[k] = anos_tipos_instituicoes[(instituicao_selecionada - 1)][i]
                            .tipos[k];
                    }
                }
            }

            $("#tipos_anexo_select option").remove();
            for (var i = 0; i < tipos_ano.length; i++) {
                $("#tipos_anexo_select").append('<option value="' + tipos_ano[i].tipo_id +
                    '">' + tipos_ano[i].nome + '</option>')
            }
        });
    });
});
</script>
<script>
$('#excluir-aba').on('click', function(event) {
    event.preventDefault()
    $('#cadastrar-aba').removeClass('active')
    $('#lixeira-aba').removeClass('active')
    $('#excluir-aba').addClass('active')
    $('#cadastrar-body').hide()
    $('#lixeira-body').hide()
    $('#excluir-body').show()
})

$('#cadastrar-aba').on('click', function(event) {
    event.preventDefault()
    $('#excluir-aba').removeClass('active')
    $('#lixeira-aba').removeClass('active')
    $('#cadastrar-aba').addClass('active')
    $('#excluir-body').hide()
    $('#lixeira-body').hide()
    $('#cadastrar-body').show()
})

$('#lixeira-aba').on('click', function(event) {
    event.preventDefault()
    $('#cadastrar-aba').removeClass('active')
    $('#excluir-aba').removeClass('active')
    $('#lixeira-aba').addClass('active')
    $('#cadastrar-body').hide()
    $('#excluir-body').hide()
    $('#lixeira-body').show()
})

$('#novo-usuario').on('click', function(event) {
    event.preventDefault()
    $('#novo-usuario').removeClass('active')
    $('#novo-usuario').addClass('not-active')
    $('#editais-anexo-a').removeClass('not-active')
    $('#editais-anexo-a').addClass('active')
    $('#card-header-edital').hide()
    $('#cadastrar-body').hide()
    $('#excluir-body').hide()
    $('#lixeira-body').hide()
    $('#card-header-usuario').show()
    $('#registrar-usuario').show()

})
</script>

<script>
    function validaFormRegistrar() {
        var oCadastrar = {

            nome_cadastro: $('#nome-input').val(),
            cpf_cadastro: $('#cpf-input').val(),
            email_cadastro: $('#email-input').val(),
            password_cadastro: $('#senha-input').val(),
            password_confirmacao_cadastro: $('#confirma-senha-input').val(),
            permissao: $('#permissao').val(),
        }

        $('#nome_cadastro_vazio').hide();
        $('#cpf_cadastro_vazio').hide();
        $('#email_cadastro_vazio').hide();
        $('#email_ja_cadastrado').hide();
        $('#password_cadastro_vazia').hide();
        $('#password_confirm_cadastro_vazia').hide();
        $('#senhas_diferentes').hide();

        if (oCadastrar.nome_cadastro == "") {
            $('#nome_cadastro_vazio').show();
            $('#nome-input').focus();
            return false;
        }
        if (oCadastrar.cpf_cadastro == "") {
            $('#cpf_cadastro_vazio').show();
            if(oCadastrar.nome_cadastro != ""){
                $('#cpf-input').focus();
            }
            return false;
        }
        if (oCadastrar.email_cadastro == "") {
            $('#email_cadastro_vazio').show();
            if (oCadastrar.nome_cadastro != "" && oCadastrar.cpf_cadastro != "") {
                $('#email-input').focus();
            }
            return false;
        }
        if (oCadastrar.password_cadastro.length < 5) {
            $('#password_cadastro_vazia').show();
            if (oCadastrar.nome_cadastro != "" && oCadastrar.cpf_cadastro != "" && oCadastrar.email_cadastro != "") {
                $('#password-input').focus();
            }
            return false;
        }
        if (oCadastrar.password_confirmacao_cadastro.length < 5) {
            $('#password_confirm_cadastro_vazia').show();
            if (oCadastrar.nome_cadastro != "" && oCadastrar.cpf_cadastro != "" && oCadastrar.email_cadastro != "" && (oCadastrar
                    .password_cadastro != "" || oCadastrar.password_cadastro.length >= 5)) {
                $('#confirma-senha-input').focus();
            }
            return false;
        }
        if (oCadastrar.password_cadastro != "" && oCadastrar.password_confirmacao_cadastro != "" &&
            oCadastrar.password_cadastro != oCadastrar.password_confirmacao_cadastro) {
            $('#senhas_diferentes').show();
            return false;
        }
        
        return true;
    }
</script>

</html>