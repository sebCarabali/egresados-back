@extends('mail.layout')

{{-- {{$user}} --}}
@isset($user)
@section('contenido')
@section('titulo_mensaje', 'Se ha registrado la empresa')
<p>Para verificar su cuenta siga el siguiente enlace</p>
<br><br>
<a href="{{env("URL_FRONT")}}egresados/confirmar/{{$user->codigo_verificacion}}">confirmar cuenta</a>
{{-- <a href="{{env("URL_FRONT")}}egresados/confirmar/{{$user->codigo_verificacion}}">confirmar cuenta</a> --}}

<br>
<p>Si usted no ha realizado el registro, por favor omita este mensaje!</p>
@endsection
@endisset

@isset($empresa)
@section('titulo_mensaje', 'Se ha registrado una nueva empresa')
@section('contenido')

<div>
      <p>
            <table class="tabla">
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
                        <tr>
                              <td colspan="2" style="text-align:center">
                                    <a class="btn-a" href="{{env("URL_FRONT")}}admin/solicitudes">EMRESAS
                                          PENDIENTES</a>
                              </td>
                        </tr>
                  </tbody>
            </table>
      </p>
</div>
@endsection
@endisset