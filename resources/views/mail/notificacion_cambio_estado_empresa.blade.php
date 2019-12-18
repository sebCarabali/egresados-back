@extends('mail.layout')
@isset($empresa)
@section('titulo_mensaje', 'Se ha cambiado el estado de la empresa')
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
                              <th>Estado</th>
                              <td>{{$empresa->estado}}</td>
                        </tr>
                        <tr>
                              <td colspan="2" style="text-align:center">
                                    <a class="btn-a" href="http://localhost:4200/login">INGRESAR</a>
                              </td>
                        </tr>
                  </tbody>
            </table>
      </p>
</div>
@endsection
@endisset