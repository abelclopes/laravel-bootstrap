@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1> Usuarios </h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th colspan="2" scope="col" >Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><a class="nav-link" href="{{ route('admin.user.edit') }}">{{ __('edit') }}</a></td>
                        <td><a class="nav-link" href="{{ route('admin.user.delete') }}">{{ __('Excluir') }}</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">Nenhum Resultado Encontrado</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
