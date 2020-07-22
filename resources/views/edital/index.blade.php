@extends('templates.master')

@section('conteudo-view')

<h1>EDITAIS</h1>
<em><strong>Recomendamos o uso dos navegadores <span style="color: #ff0000;">Mozilla Firefox <span style="color: #000000;">ou</span> Google Chrome.</span></strong></em>
<div>
    @foreach($anos as $ano)
        @if($ano->ano == $ano_selecionado )
            {{ $ano->ano }} |
        @else
            <a href="{{ route('editais.filtrarPorAno', $ano->ano) }}">{{ $ano->ano }}</a> |
        @endif
    @endforeach

    <fieldset>
        <legend>EDITAIS {{ $ano_selecionado }}</legend>
        <div>
            @foreach($tipos as $tipo)
                @if($tipo->id == $tipo_selecionado )
                    {{ $tipo->nome }} |
                @else
                    <a href="{{ route('editais.filtrarPorTipo', [$ano_selecionado,$tipo->id]) }}">{{ $tipo->nome }}</a> |
                @endif
            @endforeach
        </div>
        <div>
            @if(isset($editaisFiltrados))
                @foreach ($editaisFiltrados as $edital)
                    <a href="#" >{{ $edital->nome }}</a><br>
                @endforeach     
            
            @else
                @foreach ($editais as $edital)
                    <a href="#" >{{ $edital->nome }}</a><br>
                @endforeach
            @endif
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