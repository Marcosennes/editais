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
                        <select name="instituicao_id" class="form-control">
                            @foreach ($instituicoes as $instituicao)
                                <option value="{{ $instituicao->id }}">{{ $instituicao->nome }}</option>                                            
                            @endforeach          
                        </select>
                    <label style="margin-top: 10px;" for="edital">Nome do edital</label>
                        <input type="text" class="form-control" name="nome">
                    <label style="margin-top: 10px;" for="arquivo">Selecione o arquivo</label>
                        <input type="file" name="arquivo" id="arquivo">
                        <br>
                    <label style="margin-top: 10px;" for="tipo">Tipo</label>
                        <select name="tipo_id" class="form-control"> 
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>                                            
                            @endforeach                    
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
                <form name="formFiltraAnexo">
                    {{ csrf_field() }}
                    <label for="instituicao">Instituição</label>
                        <select name="instituicao_id" class="form-control">
                            @foreach ($instituicoes as $instituicao)
                                <option value="{{ $instituicao->id }}">{{ $instituicao->nome }}</option>                                            
                            @endforeach          
                        </select>
                    <label for="ano">Ano</label>
                    <select name="ano" id="anexoAno" class="form-control">
                        @foreach ($anos as $ano)
                            <option value="{{ $ano->ano }}">{{ $ano->ano }}</option>            
                        @endforeach
                    </select>
                    <label for="tipo">Tipo</label>
                    <select name="tipo" id="anexoTipo" class="form-control">
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>            
                        @endforeach
                    </select>
                    <button class="btn btn-primary" style="width: 100%; margin-top: 11px; margin-bottom: 20px;" type="submit">Filtrar</button>
                </form>
                <form method="post" action="{{ route('editalAnexo.salvar') }}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
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
                @if (session('cadastroAnexo'))
                @if(session('cadastroAnexo')['validacao'] == true)
                    <div class="alert alert-success" role="alert">
                      {{ session('cadastroAnexo')['mensagem'] }}
                    </div>
                @endif
                @if(session('cadastroAnexo')['validacao'] == false)
                    <div class="alert alert-danger" role="alert">
                      {{ session('cadastroAnexo')['mensagem'] }}
                    </div>
                @endif
            @endif
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
    <script type="text/javascript">
            $('form[name="formFiltraAnexo"]').submit(function(event){
                event.preventDefault();

                /*
                var instituicao = $(this).find('#anexoInstituicao').val();
                var ano         = $(this).find('#anexoAno').val();
                var tipo        = $(this).find('#anexoTipo').val();
                */

                $.ajax({
                    url: "{{ route('edital.filtrarAnexo') }}",
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
                        console.log(response);
                    }
                });

            });
    </script>
@endsection