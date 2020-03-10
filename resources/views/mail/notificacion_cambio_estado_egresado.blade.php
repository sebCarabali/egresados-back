@extends('mail.layout')
@isset($oferta)
@section('titulo_mensaje', 'Se ha cambiado el estado del egresado')
@section('contenido')
<div>
      <p>
            <table class="tabla">
                  <thead>
                        <th colspan="2">ESTADO ACTUALIZADO</th>
                  </thead>
                  <tbody>
                        <tr>
                              <th>Oferta</th>
                              <td>{{$oferta->nombre_oferta}}</td>
                        </tr>
                        <tr>
                              <th>Egresado</th>
                              <td>{{$egresado->nombres}} {{$egresado->apellidos}}</td>
                        </tr>
                        <tr>
                              <th>Estado</th>
                              <td>{{$estado}}</td>
                        </tr>
                        <tr>
                              <td colspan="2" style="text-align:center">
                                    <a class="btn-a" href="{{env("URL_FRONT")}}login">INGRESAR</a>
                              </td>
                        </tr>
                  </tbody>
            </table>
      </p>
</div>
@endsection
@endisset