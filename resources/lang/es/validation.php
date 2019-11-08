<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => ':ATTRIBUTE debe ser aceptado.',
    'active_url'           => ':ATTRIBUTE no es una URL válida.',
    'after'                => ':ATTRIBUTE debe ser una fecha posterior a :date.',
    'after_or_equal'       => ':ATTRIBUTE debe ser una fecha posterior o igual a :date.',
    'alpha'                => ':ATTRIBUTE sólo debe contener letras.',
    'alpha_dash'           => ':ATTRIBUTE sólo debe contener letras, números y guiones.',
    'alpha_num'            => ':ATTRIBUTE sólo debe contener letras y números.',
    'array'                => ':ATTRIBUTE debe ser un conjunto.',
    'before'               => ':ATTRIBUTE debe ser una fecha anterior a :date.',
    'before_or_equal'      => ':ATTRIBUTE debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'numeric' => ':ATTRIBUTE tiene que estar entre :min - :max.',
        'file'    => ':ATTRIBUTE debe pesar entre :min - :max kilobytes.',
        'string'  => ':ATTRIBUTE tiene que tener entre :min - :max caracteres.',
        'array'   => ':ATTRIBUTE tiene que tener entre :min - :max ítems.',
    ],
    'boolean'              => 'El campo :ATTRIBUTE debe tener un valor verdadero o falso.',
    'confirmed'            => 'La confirmación de :ATTRIBUTE no coincide.',
    'date'                 => ':ATTRIBUTE no es una fecha válida.',
    'date_equals'          => ':ATTRIBUTE debe ser una fecha igual a :date.',
    'date_format'          => ':ATTRIBUTE no corresponde al formato :format.',
    'different'            => ':ATTRIBUTE y :other deben ser diferentes.',
    'digits'               => ':ATTRIBUTE debe tener :digits dígitos.',
    'digits_between'       => ':ATTRIBUTE debe tener entre :min y :max dígitos.',
    'dimensions'           => 'Las dimensiones de la imagen :ATTRIBUTE no son válidas.',
    'distinct'             => 'El campo :ATTRIBUTE contiene un valor duplicado.',
    'email'                => ':ATTRIBUTE no es un correo válido',
    'ends_with'            => 'El campo :ATTRIBUTE debe finalizar con uno de los siguientes valores: :values',
    'exists'               => ':ATTRIBUTE es inválido.',
    'file'                 => 'El campo :ATTRIBUTE debe ser un archivo.',
    'filled'               => 'El campo :ATTRIBUTE es obligatorio.',
    'gt'                   => [
        'numeric' => 'El campo :ATTRIBUTE debe ser mayor que :value.',
        'file'    => 'El campo :ATTRIBUTE debe tener más de :value kilobytes.',
        'string'  => 'El campo :ATTRIBUTE debe tener más de :value caracteres.',
        'array'   => 'El campo :ATTRIBUTE debe tener más de :value elementos.',
    ],
    'gte'                  => [
        'numeric' => 'El campo :ATTRIBUTE debe ser como mínimo :value.',
        'file'    => 'El campo :ATTRIBUTE debe tener como mínimo :value kilobytes.',
        'string'  => 'El campo :ATTRIBUTE debe tener como mínimo :value caracteres.',
        'array'   => 'El campo :ATTRIBUTE debe tener como mínimo :value elementos.',
    ],
    'image'                => ':ATTRIBUTE debe ser una imagen.',
    'in'                   => ':ATTRIBUTE es inválido.',
    'in_array'             => 'El campo :ATTRIBUTE no existe en :other.',
    'integer'              => ':ATTRIBUTE debe ser un número entero.',
    'ip'                   => ':ATTRIBUTE debe ser una dirección IP válida.',
    'ipv4'                 => ':ATTRIBUTE debe ser un dirección IPv4 válida',
    'ipv6'                 => ':ATTRIBUTE debe ser un dirección IPv6 válida.',
    'json'                 => 'El campo :ATTRIBUTE debe tener una cadena JSON válida.',
    'lt'                   => [
        'numeric' => 'El campo :ATTRIBUTE debe ser menor que :value.',
        'file'    => 'El campo :ATTRIBUTE debe tener menos de :value kilobytes.',
        'string'  => 'El campo :ATTRIBUTE debe tener menos de :value caracteres.',
        'array'   => 'El campo :ATTRIBUTE debe tener menos de :value elementos.',
    ],
    'lte'                  => [
        'numeric' => 'El campo :ATTRIBUTE debe ser como máximo :value.',
        'file'    => 'El campo :ATTRIBUTE debe tener como máximo :value kilobytes.',
        'string'  => 'El campo :ATTRIBUTE debe tener como máximo :value caracteres.',
        'array'   => 'El campo :ATTRIBUTE debe tener como máximo :value elementos.',
    ],
    'max'                  => [
        'numeric' => ':ATTRIBUTE no debe ser mayor a :max.',
        'file'    => ':ATTRIBUTE no debe ser mayor que :max kilobytes.',
        'string'  => ':ATTRIBUTE no debe ser mayor que :max caracteres.',
        'array'   => ':ATTRIBUTE no debe tener más de :max elementos.',
    ],
    'mimes'                => ':ATTRIBUTE debe ser un archivo con formato: :values.',
    'mimetypes'            => ':ATTRIBUTE debe ser un archivo con formato: :values.',
    'min'                  => [
        'numeric' => 'El tamaño de :ATTRIBUTE debe ser de al menos :min.',
        'file'    => 'El tamaño de :ATTRIBUTE debe ser de al menos :min kilobytes.',
        'string'  => ':ATTRIBUTE debe contener al menos :min caracteres.',
        'array'   => ':ATTRIBUTE debe tener al menos :min elementos.',
    ],
    'not_in'               => ':ATTRIBUTE es inválido.',
    'not_regex'            => 'El formato del campo :ATTRIBUTE no es válido.',
    'numeric'              => ':ATTRIBUTE debe ser numérico.',
    'password'             => 'La contraseña es incorrecta.',
    'present'              => 'El campo :ATTRIBUTE debe estar presente.',
    'regex'                => 'El formato de :ATTRIBUTE es inválido.',
    'required'             => 'El campo :ATTRIBUTE es obligatorio.',
    'required_if'          => 'El campo :ATTRIBUTE es obligatorio cuando :other es :value.',
    'required_unless'      => 'El campo :ATTRIBUTE es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo :ATTRIBUTE es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo :ATTRIBUTE es obligatorio cuando :values está presente.',
    'required_without'     => 'El campo :ATTRIBUTE es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :ATTRIBUTE es obligatorio cuando ninguno de :values estén presentes.',
    'same'                 => ':ATTRIBUTE y :other deben coincidir.',
    'size'                 => [
        'numeric' => 'El tamaño de :ATTRIBUTE debe ser :size.',
        'file'    => 'El tamaño de :ATTRIBUTE debe ser :size kilobytes.',
        'string'  => ':ATTRIBUTE debe contener :size caracteres.',
        'array'   => ':ATTRIBUTE debe contener :size elementos.',
    ],
    'starts_with'          => 'El campo :ATTRIBUTE debe comenzar con uno de los siguientes valores: :values',
    'string'               => 'El campo :ATTRIBUTE debe ser una cadena de caracteres.',
    'timezone'             => 'El :ATTRIBUTE debe ser una zona válida.',
    'unique'               => 'El campo :ATTRIBUTE ya ha sido registrado.',
    'uploaded'             => 'Subir :ATTRIBUTE ha fallado.',
    'url'                  => 'El formato :ATTRIBUTE es inválido.',
    'uuid'                 => 'El campo :ATTRIBUTE debe ser un UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'password' => [
            'min' => 'La :ATTRIBUTE debe contener más de :min caracteres',
        ],
        'email'    => [
            'unique' => 'El :ATTRIBUTE ya ha sido registrado.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'address'               => 'dirección',
        'age'                   => 'edad',
        'body'                  => 'contenido',
        'city'                  => 'ciudad',
        'content'               => 'contenido',
        'country'               => 'país',
        'date'                  => 'fecha',
        'day'                   => 'día',
        'description'           => 'descripción',
        'email'                 => 'correo electrónico',
        'excerpt'               => 'extracto',
        'first_name'            => 'nombre',
        'gender'                => 'género',
        'hour'                  => 'hora',
        'last_name'             => 'apellido',
        'message'               => 'mensaje',
        'minute'                => 'minuto',
        'mobile'                => 'móvil',
        'month'                 => 'mes',
        'name'                  => 'nombre',
        'password'              => 'contraseña',
        'password_confirmation' => 'confirmación de la contraseña',
        'phone'                 => 'teléfono',
        'price'                 => 'precio',
        'second'                => 'segundo',
        'sex'                   => 'sexo',
        'subject'               => 'asunto',
        'terms'                 => 'términos',
        'time'                  => 'hora',
        'title'                 => 'título',
        'username'              => 'usuario',
        'year'                  => 'año',
        'datos-cuenta' => [
            'email' => 'Correo',
            'contrasenia' => 'contraseña'
        ],
        'datos-generales-empresa' => [
            'numEmpleados' => 'Número de empleados',
            'NIT' => 'NIT',
            'razonSocial' => 'Razon Social',
            'nombreEmpresa' => 'Nombre Empresa',
            'anioCreacion' => 'Año de creación',
            'ingresosEmp' => 'Ingresos Empresa',
            'descripcionEmpresa' => 'Descripción',
        ],
        'sectores' => [
            'sectores' => [
                '*' => 'Sub-sector'
            ]
        ],
        'loc-contact-empresa' => [
            'ciudadEmp' => 'Ciudad Empresa',
            'direccionEmp' => 'Dirección Empresa',
            'barrioEmp' => 'Barrio Empresa',
            'codigoPostalEmp' => 'Código Empresa',
            'telefonoEmp' => 'Teléfono postal',
            'emailEmp' => 'Correo Empresa',
            'sitioWebEmp' => 'Sitio Web Empresa',
        ],
        'datos-resp' => [
            'nombrereplegal' => 'Nombre Representante',
            'apellidoreplegal' => 'Apellido Representante',
            'telefonoreplegal' => 'Teléfono Representante',
            'nombrereplegal' => 'Nombre Representante',
            'telefonoMovilreplegal' => 'Teléfono movil Representante',
        ],
        'datos-resp' => [
            'nombreResp' => 'Nombre Administrador',
            'apellidoResp' => 'Apellido Administrador',
            'cargo' => 'Cargo Administrador',
            'telefonoResp' => 'Teléfono Administrador',
            'telefonoMovilResp' => 'Teléfono movil Administrador',
            'horarioContactoResp' => 'Horario Administrador',
            'direccionTrabajoResp' => 'Dirección Administrador',
            'emailCorpResp' => 'Correo Corporativo Administrador',
        ],
        'archivos' => [
            'logo' => 'logo',
            'camaraycomercio' => 'cámara y comercio'
        ]
    ],
];
