@extends('templates.master')

@section('conteudo-view')

<h1>EDITAIS</h1>
<label for="instituicao">Instituição</label><br>
@foreach ($instituicoes as $instituicao)
    <a href="{{ route('editais.filtrar', [$instituicao->id, 2019, 1]) }}">{{ $instituicao->nome }}</a> |                                       
@endforeach          
<br><em><strong>Recomendamos o uso dos navegadores <span style="color: #ff0000;">Mozilla Firefox <span style="color: #000000;">ou</span> Google Chrome.</span></strong></em>
<div>
    @foreach($anos as $ano)
        @if($ano->ano == $ano_selecionado )
            {{ $ano->ano }} |
        @else
            <a href="{{ route('editais.filtrar', [$instituicao_selecionada, $ano->ano, 1]) }}">{{ $ano->ano }}</a> |
        @endif
    @endforeach

    <fieldset>
        <legend>EDITAIS {{ $ano_selecionado }}</legend>
        <div>
            @foreach($tipos as $tipo)
                @if($tipo->id == $tipo_selecionado )
                    {{ $tipo->nome }} |
                @else
                    <a href="{{ route('editais.filtrar', [$instituicao_selecionada, $ano_selecionado,$tipo->id]) }}">{{ $tipo->nome }}</a> |
                @endif
            @endforeach
        </div>
        <div>
            @foreach ($editais as $edital)
                <a href="#">{{ $edital->nome }}</a><br>
                @for ($i = 0; $i < sizeof($editais_com_anexos); $i++)
                    @if ($edital->id == $editais_com_anexos[$i] )
                        @foreach ($anexos as $anexo)
                            @if ($edital->id == $anexo->pai_id)
                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                <a href="#">{{ $anexo->nome }}</a><br>
                            @endif
                        @endforeach
                    @endif
                @endfor
            @endforeach
        </div>
    </fieldset>
</div>
@endsection

@section('js')

<script>
    $(document).ready(function() {
    });
</script>

@endsection