<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Correo de confirmación</title>
    <style>
      
      body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 1rem;
        background-color: #fff;
      }

      .mail-wrapper {
        box-sizing: border-box;
        margin: 0 auto;
        width: 70%;
        text-align: center;
        background-color: #fff;
      }

      .mail-wrapper .mail-description {
        padding: 10px;
        font-size: 0.9rem;
      }

      .mail-wrapper .mail-title {
        padding: 10px;
        color: #990000;
      }

      .mail-wrapper .mail-welcome-message {
        margin-top: 20px;
        margin-bottom: 20px;
        padding: 10px;
      }

      .mail-wrapper .mail-welcome-message img {
        width: 100%;
      }

      .mail-wrapper .mail-footer {
        color: #990000;
        font-style: italic;
        font-weight: bold;
        margin-bottom: 20px;
      }

    </style>
  </head>
  <body>
    <div class="mail-wrapper">
      <div class="mail-title">
        <b>BIENVENIDOS AL ÁREA DE EGRESADOS</b>
      </div>
      <div class="mail-description">
        Para conocer los servicios y beneficios que el Área de Egresados y la
        Universidad del Cauca tiene para tí, lo invitamos a activar su cuenta en
        el siguiente enlace:
      </div>
      <div class="mail-link">
        <a href="{{env("URL_FRONT")}}egresados/confirmar/{{$codigo}}">
          {{env("URL_FRONT")}}egresados/confirmar/{{$codigo}}
        </a>
      </div>
      <div class="mail-welcome-message">
        <img src="https://raw.githubusercontent.com/Fher95/OfertasEgresadosFront/desarrollo/src/assets/img/Mensaje%20Bienvenida_Mesa%20de%20trabajo%201.jpg" />
      </div>
      <div class="mail-footer">
        ¡ Los egresados son un pilar fundamental para la excelencia
        institucional !
      </div>
    </div>
  </body>
</html>