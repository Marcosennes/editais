<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Importando o Font Awesome -->
    <script src="https://kit.fontawesome.com/a65264833f.js" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <link href = "https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
 
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fredoka+One">
    <link rel="stylesheet" href="{{ asset('css/stylesheet.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <title>Investindo</title>   
    
</head>
<body>
<div class="offset-lg-2 col-12 col-md-12 col-lg-8" style="background-color: white">
    <div class="container">
        <div class="offset-2 col-8">
            <div class="row d-flex flex-column">
                <form method="post" action="{{ route('edital.salvar') }}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <h3 style="margin-top: 20px;">Cadastrar edital</h3>
                    <label for="instituicao">Instituição</label>
                        <select name="instituicao" class="form-control">
                            <option value="1">Prefeitura de Maricá</option>            
                            <option value="2">SOMAR</option>            
                        </select>
                    <label style="margin-top: 10px;" for="edital">Nome do edital</label>
                        <input type="text" class="form-control" name="nome">
                    <label style="margin-top: 10px;" for="arquivo">Selecione o arquivo</label>
                        <input type="file" name="arquivo" id="arquivo">
                        <br>
                    <label style="margin-top: 10px;" for="tipo">Tipo</label>
                        <select name="tipo_id" class="form-control">
                            <option value="1">Pregão Presencial</option>            
                            <option value="2">Convite</option>            
                            <option value="3">Concorrência Pública</option>            
                            <option value="4">Tomada de Preços</option>            
                        </select>
                    <label style="margin-top: 10px;" for="ano">Ano</label>
                        <input type="text" onkeypress="return onlynumber();" class="form-control" name="ano">
                    <!--
                    <label for="anexo">O edital possui anexo?</label>
                    <a id="adicionar_anexo" class="btn btn-1">Anexar arquivo neste edital</a>
                    -->
                    <button class="btn btn-1" style="width: 100%; margin-top: 11px;" type="submit">Cadastrar</button>
                </form>    
                @if (session('cadastro'))
                    @if(session('cadastro')['validacao'] == true)
                        <div class="alert alert-success" role="alert">
                          {{ session('cadastro')['mensagem'] }}
                        </div>
                    @endif
                    @if(session('cadastro')['validacao'] == false)
                        <div class="alert alert-danger" role="alert">
                          {{ session('cadastro')['mensagem'] }}
                        </div>
                    @endif
                @endif
                <form method="post" action="" enctype="multipart/form-data">
                    <h3 style="margin-top: 40px;">Cadastrar anexo</h3>
                    <div id="anexo" style="display: show;">
                        <label for="instituicao">Instituição</label>
                            <select name="instituicao" class="form-control">
                                <option value="1">Prefeitura de Maricá</option>            
                                <option value="2">SOMAR</option>            
                            </select>
                        <div class="table-overflow" style="margin-top: 20px; max-height:400px; overflow-y:auto;">
                            <table class="table table-sm table-striped table-bordered table-hover" style="background-color: white">
                                <thead>
                                  <tr>
                                    <th scope="col">Nome do edital</th>
                                    <th scope="col">Ano</th>
                                    <th scope="col">Selecione</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital_pai" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td scope="row">Pregão Presencial 02</td>
                                    <td>2019</td>
                                    <td>
                                        <input type="radio" name="edital" value="id"><br>                                        </div>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                        </div>
                        <label style="margin-top: 10px;" for="edital">Nome do anexo</label>
                            <input type="text" class="form-control" name="name">
                        <label style="margin-top: 10px;" for="arquivo">Selecione o arquivo</label>
                            <input type="file" name="arquivo" id="arquivo">    
                            <br>
                        <button class="btn btn-1" style="width: 100%; margin-top: 11px; margin-bottom: 20px;" type="submit">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

  </body>
</html>

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

<script>
        $(document).ready(function()
        {
            $("#adicionar_anexo").click(function()
            {
                $('#adicionar_anexo').hide();
                $("#anexo").show();
            })
        })
    </script>