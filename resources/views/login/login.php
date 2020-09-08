<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Login</title>

    <link rel="stylesheet" href="css/stylesheet.css">

    <!-- Fonte Google -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fredoka+One">

    <!--jquery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Importando o Font Awesome -->
    <script src="https://kit.fontawesome.com/a65264833f.js" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fredoka+One">
</head>

<body>
    <section id="conteudo-view" class="login">
        <div id="login">
            <h3 class="text-center text-white pt-5">Login form</h3>
            <div class="container">
                <div id="login-row" class="row justify-content-center align-items-center">
                    <div id="login-column" class="col-md-6">
                        <div id="login-box" class="col-md-12">
                            <form id="login-form" class="form" action="/efetua_login" method="post">
                                <?php echo csrf_field(); ?>
                                <h3 class="text-center text-info">Login</h3>
                                <div class="form-group">
                                    <label for="username" class="text-info">Email:</label><br>
                                    <input type="email" name="email" id="username" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="text-info">Senha:</label><br>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                                </div>
                                <?php 
                                    session_start();
                                    if(isset($_SESSION['usuario_nao_encontrado'])){
                                        echo '<p style="color: red;" role="alert">' . $_SESSION['usuario_nao_encontrado']['mensagem'] . '</p>';
                                        unset($_SESSION['usuario_nao_encontrado']);
                                    }
                                    if(isset($_SESSION['senha_errada'])){
                                        echo '<p style="color: red;" role="alert">' . $_SESSION['senha_errada']['mensagem'] . '</p>';
                                        unset($_SESSION['senha_errada']);
                                    }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

<script>
$(document).ready(function() {
    $('#form_register').submit(function(event) {

        var oCadastrar = {
            cpf: $('#cpf').val(),
            nome_cadastro: $('#usernamesignup').val(),
            email_cadastro: $('#emailsignup').val(),
            password_cadastro: $('#passwordsignup').val(),
            password_confirmacao_cadastro: $('#passwordsignup_confirm').val(),
        }

        $('#nome_cadastro_vazio').hide();
        $('#email_cadastro_vazio').hide();
        $('#email_ja_cadastrado').hide();
        $('#password_cadastro_vazia').hide();
        $('#password_confirm_cadastro_vazia').hide();
        $('#senhas_diferentes').hide();

        if (oCadastrar.nome_cadastro == '' || oCadastrar.email_cadastro == '' || oCadastrar
            .password_cadastro.length < 5 ||
            oCadastrar.password_cadastro == "" || oCadastrar.password_confirmacao_cadastro == "" ||
            oCadastrar.password_cadastro != oCadastrar.password_confirmacao_cadastro) {
            if (oCadastrar.nome_cadastro == "") {
                $('#nome_cadastro_vazio').show();
                $('#usernamesignup').focus();
            }
            if (oCadastrar.email_cadastro == "") {
                $('#email_cadastro_vazio').show();
                if (oCadastrar.nome_cadastro != "" && oCadastrar.email_cadastro == "") {
                    $('#emailsignup').focus();
                }
            }
            if (oCadastrar.password_cadastro.length < 5) {
                $('#password_cadastro_vazia').show();

                if (oCadastrar.nome_cadastro != "" && oCadastrar.email_cadastro != "" && oCadastrar
                    .password_cadastro == "") {
                    $('#passwordsignup').focus();
                }
            }
            if (oCadastrar.password_confirmacao_cadastro.length < 5) {
                $('#password_confirm_cadastro_vazia').show();

                if (oCadastrar.nome_cadastro != "" && oCadastrar.email_cadastro != "" && (oCadastrar
                        .password_cadastro != "" || oCadastrar.password_cadastro.length >= 5) &&
                    oCadastrar.password_confirmacao_cadastro == "") {
                    $('#passwordsignup_confirm').focus();
                }
            }
            if (oCadastrar.password_cadastro != "" && oCadastrar.password_confirmacao_cadastro != "" &&
                oCadastrar.password_cadastro != oCadastrar.password_confirmacao_cadastro) {
                $('#senhas_diferentes').show();
            }
        }
        event.preventDefault();
    });
});
</script>