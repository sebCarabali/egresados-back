@extends('mail.layout')

@isset($oferta)
@section('titulo_mensaje', 'Se ha registrado una nueva oferta')
@section('contenido')

<div>
      <p>
            <table class="tabla">
                  <thead>
                        <th colspan="2">DATOS DE LA OFERTA</th>
                  </thead>
                  <tbody>
                        <tr>
                              <th>Empresa</th>
                              <td>{{$oferta->empresa->nombre}}</td>
                        </tr>
                        <tr>
                              <th>Oferta</th>
                              <td>{{$oferta->nombre_oferta}}</td>
                        </tr>
                        <tr>
                              <th>Descripción</th>
                              <td>{{$oferta->nombre_oferta}}</td>
                        </tr>
                        <tr>
                              <th>Estado</th>
                              <td>{{$oferta->estado}}</td>
                        </tr>
                        <tr>
                              <td colspan="2" style="text-align:center">
                                    <a class="btn-a" href="http://localhost:4200/admin/ofertasLaborales">LISTAR OFERTAS</a>
                              </td>
                        </tr>
                  </tbody>
            </table>
      </p>
</div>
@endsection
@endisset
