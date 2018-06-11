@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Cerrar Sesion</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Para cerrar sesion presione en la flecha junto a "Administrador"
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
