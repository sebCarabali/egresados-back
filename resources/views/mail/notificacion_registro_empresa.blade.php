@extends('mail.layout')
@section('titulo_mensaje', 'Se ha registrado la empresa')
@section('contenido')
{{-- {{$user}} --}}
      <p>Para verificar su cuenta siga el siguiente enlace <br><br>
      {{-- <a href="{{env("URL_FRONT")}}egresados/confirmar/{{$user->codigo_verificacion}}">confirmar cuenta</a></p> --}}
      <a href="http://localhost:4200/egresados/confirmar/{{$user->codigo_verificacion}}">confirmar cuenta</a></p>
      <br>
      <p>Si usted no ha realizado el registro, por favor omita este mensaje!</p>
@endsection