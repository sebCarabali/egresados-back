@extends('mail.layout')

@section('contenido')
{{-- {{$user}} --}}
@isset($user)
@section('titulo_mensaje', 'Se ha registrado la empresa')
<p>Para verificar su cuenta siga el siguiente enlace <br><br>
      {{-- <a href="{{env("URL_FRONT")}}egresados/confirmar/{{$user->codigo_verificacion}}">confirmar cuenta</a></p>
--}}
<a href="http://localhost:4200/egresados/confirmar/{{$user->codigo_verificacion}}">confirmar cuenta</a></p>
<br>
<p>Si usted no ha realizado el registro, por favor omita este mensaje!</p>
@endisset
@section('titulo_mensaje', 'Se ha registrado una nueva empresa')
@isset($empresa)

<div>
      <table>
            <thead>
                  <th colspan="2">DATOS DE LA EMPRESA</th>
            </thead>
            <tbody>
                  <tr>
                        <th>NIT</th>
                        <td>{{$empresa->nit}}</td>
                  </tr>
                  <tr>
                        <th>Nombre</th>
                        <td>{{$empresa->nombre}}</td>
                  </tr>
            </tbody>
      </table>
</div>

@endisset
@endsection