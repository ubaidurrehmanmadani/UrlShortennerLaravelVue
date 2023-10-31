@extends('layouts.app')

@section('content')

<div class="sidenav">
    <a href="javascript:void(0)" id="companies" onclick="loadView(this.id)">Companies</a>
    <a href="javascript:void(0)" id="employees" onclick="loadView(this.id)">Employees</a>
</div>

<div class="main" id="app">
    <div id="companies_id" style="display: none">
        <companies-component></companies-component>
    </div>
    <div id="employees_id" style="display: none">
        <employees-component></employees-component>
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
