<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validación - Traducciones al español
    |--------------------------------------------------------------------------
    |
    | Mensajes de validación personalizados y nombres de atributos.
    |
    */

    'accepted'             => 'El :attribute debe ser aceptado.',
    'active_url'           => 'El :attribute no es una URL válida.',
    'after'                => 'El :attribute debe ser una fecha posterior a :date.',
    'alpha'                => 'El :attribute sólo debe contener letras.',
    'alpha_dash'           => 'El :attribute sólo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num'            => 'El :attribute sólo debe contener letras y números.',
    'array'                => 'El :attribute debe ser un conjunto.',
    'before'               => 'El :attribute debe ser una fecha anterior a :date.',

    'between' => [
        'numeric' => 'El :attribute debe estar entre :min y :max.',
        'file'    => 'El :attribute debe pesar entre :min y :max kilobytes.',
        'string'  => 'El :attribute debe contener entre :min y :max caracteres.',
        'array'   => 'El :attribute debe contener entre :min y :max elementos.',
    ],

    'boolean'              => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed'            => 'La confirmación de :attribute no coincide.',
    'date'                 => 'El :attribute no corresponde con una fecha válida.',
    'date_format'          => 'El :attribute no corresponde al formato :format.',
    'different'            => 'Los :attribute y :other deben ser diferentes.',
    'digits'               => 'El :attribute debe tener :digits dígitos.',
    'digits_between'       => 'El :attribute debe tener entre :min y :max dígitos.',
    'dimensions'           => 'Las dimensiones de la imagen :attribute no son válidas.',
    'distinct'             => 'El campo :attribute tiene un valor duplicado.',
    'email'                => 'El :attribute debe ser una dirección de correo válida.',
    'exists'               => 'El :attribute seleccionado no es válido.',
    'file'                 => 'El :attribute debe ser un archivo.',
    'filled'               => 'El campo :attribute es obligatorio.',

    'gt' => [
        'numeric' => 'El :attribute debe ser mayor que :value.',
        'file'    => 'El :attribute debe pesar más de :value kilobytes.',
        'string'  => 'El :attribute debe contener más de :value caracteres.',
        'array'   => 'El :attribute debe contener más de :value elementos.',
    ],

    'gte' => [
        'numeric' => 'El :attribute debe ser mayor o igual que :value.',
        'file'    => 'El :attribute debe pesar como mínimo :value kilobytes.',
        'string'  => 'El :attribute debe contener como mínimo :value caracteres.',
        'array'   => 'El :attribute debe contener como mínimo :value elementos.',
    ],

    'image'                => 'El :attribute debe ser una imagen.',
    'in'                   => 'El :attribute seleccionado no es válido.',
    'in_array'             => 'El campo :attribute no existe en :other.',
    'integer'              => 'El :attribute debe ser un número entero.',
    'ip'                   => 'El :attribute debe ser una dirección IP válida.',
    'json'                 => 'El :attribute debe ser una cadena JSON válida.',

    'lt' => [
        'numeric' => 'El :attribute debe ser menor que :value.',
        'file'    => 'El :attribute debe pesar menos de :value kilobytes.',
        'string'  => 'El :attribute debe contener menos de :value caracteres.',
        'array'   => 'El :attribute debe contener menos de :value elementos.',
    ],

    'lte' => [
        'numeric' => 'El :attribute debe ser menor o igual que :value.',
        'file'    => 'El :attribute debe pesar como máximo :value kilobytes.',
        'string'  => 'El :attribute debe contener como máximo :value caracteres.',
        'array'   => 'El :attribute debe contener como máximo :value elementos.',
    ],

    'max' => [
        'numeric' => 'El :attribute no puede ser mayor que :max.',
        'file'    => 'El :attribute no puede pesar más de :max kilobytes.',
        'string'  => 'El :attribute no puede contener más de :max caracteres.',
        'array'   => 'El :attribute no puede contener más de :max elementos.',
    ],

    'mimes'                => 'El :attribute debe ser un archivo con formato: :values.',
    'mimetypes'            => 'El :attribute debe ser un archivo con formato: :values.',

    'min' => [
        'numeric' => 'El :attribute debe ser al menos :min.',
        'file'    => 'El :attribute debe pesar al menos :min kilobytes.',
        'string'  => 'El :attribute debe contener al menos :min caracteres.',
        'array'   => 'El :attribute debe contener al menos :min elementos.',
    ],

    'not_in'               => 'El :attribute seleccionado no es válido.',
    'numeric'              => 'El :attribute debe ser numérico.',
    'present'              => 'El campo :attribute debe estar presente.',
    'regex'                => 'El formato de :attribute no es válido.',
    'required'             => 'El campo :attribute es obligatorio.',
    'required_if'          => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_unless'      => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values están presentes.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values está presente.',
    'same'                 => 'Los :attribute y :other deben coincidir.',

    'size' => [
        'numeric' => 'El :attribute debe ser :size.',
        'file'    => 'El :attribute debe pesar :size kilobytes.',
        'string'  => 'El :attribute debe contener :size caracteres.',
        'array'   => 'El :attribute debe contener :size elementos.',
    ],

    'string'               => 'El :attribute debe ser una cadena de caracteres.',
    'timezone'             => 'El :attribute debe ser una zona válida.',
    'unique'               => 'El :attribute ya está en uso.',
    'uploaded'             => 'El :attribute no se pudo subir.',
    'url'                  => 'El formato de :attribute no es válido.',

    /*
    |--------------------------------------------------------------------------
    | Atributos
    |--------------------------------------------------------------------------
    |
    | Aquí se especifican los nombres legibles de los atributos usados en los
    | mensajes de validación. Esto hará que el texto sea más contextual.
    |
    */
    'attributes' => [
        'name' => 'nombre',
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
        'address' => 'dirección',
        'telefono' => 'teléfono',
        'phone' => 'teléfono',
        'direccion' => 'dirección',
        'company' => 'empresa',
    ],
    /* Mensajes personalizados por atributo */
    'custom' => [
        'email' => [
            'unique' => 'No se puede ingresar un correo que ya está registrado.',
        ],
    ],
];
