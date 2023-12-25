@extends('layouts.app')

@section('content')

<div class="sidenav">
    <a href="javascript:void(0)" id="url_shortener" onclick="loadView(this.id)">Url Manager</a>
</div>

<div class="main" id="app">
    <div id="urls_id">
        <url-component></url-component>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
