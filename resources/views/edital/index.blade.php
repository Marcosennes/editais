@extends('templates.master')

@section('conteudo-view')

<h1>EDITAIS</h1>
<em><strong>Recomendamos o uso dos navegadores <span style="color: #ff0000;">Mozilla Firefox <span style="color: #000000;">ou</span> Google Chrome.</span></strong></em>
<div>
    @foreach($anos as $ano)
        <a href="#">{{ $ano }}</a> |
    @endforeach
</div>

