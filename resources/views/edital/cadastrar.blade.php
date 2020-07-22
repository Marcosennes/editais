@extends('templates.master')

@section('conteudo-view')
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
                    <button class="btn btn-primary" style="width: 100%; margin-top: 11px;" type="submit">Cadastrar</button>
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

                <h3 style="margin-top: 40px;">Cadastrar anexo</h3>
                <form method="post" action="{{ route('editais.filtrarPorTipoAnexo') }}">
                    {{ csrf_field() }}
                    <label for="instituicao">Instituição</label>
                    <select name="instituicao" class="form-control">
                        <option value="1">Prefeitura de Maricá</option>            
                        <option value="2">SOMAR</option>            
                    </select>
                    <label for="ano">Ano</label>
                    <select name="ano" class="form-control">
                        @foreach ($anos as $ano)
                            <option value="{{ $ano->ano }}">{{ $ano->ano }}</option>            
                        @endforeach
                    </select>
                    <label for="tipo">Tipo</label>
                    <select name="tipo" class="form-control">
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>            
                        @endforeach
                    </select>
                    <button class="btn btn-primary" style="width: 100%; margin-top: 11px; margin-bottom: 20px;" type="submit">Filtrar</button>
                </form>
                <form method="post" action="" enctype="multipart/form-data">
                    <div id="anexo" style="display: show;">
                        <div class="table-overflow" style="margin-top: 20px; max-height:400px; overflow-y:auto;">
                            @if (isset($editaisFiltrados))
                                <table class="table table-sm table-striped table-bordered table-hover" style="background-color: white">
                                    <thead>
                                    <tr>
                                        <th scope="col">Nome do edital</th>
                                        <th scope="col">Ano</th>
                                        <th scope="col">Selecione</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($editaisFiltrados as $edital)
                                            <tr>
                                                <td scope="row">{{ $edital->nome }}</td>
                                                <td>{{ $edital->ano }}</td>
                                                <td>
                                                    <input type="radio" name="edital_pai" value="id"><br>                                        
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        <label style="margin-top: 10px;" for="edital">Nome do anexo</label>
                            <input type="text" class="form-control" name="name">
                        <label style="margin-top: 10px;" for="arquivo">Selecione o arquivo</label>
                            <input type="file" name="arquivo" id="arquivo">    
                            <br>
                        <button class="btn btn-primary" style="width: 100%; margin-top: 11px; margin-bottom: 20px;" type="submit">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

@section('js')
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
@endsection