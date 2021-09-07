<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute 必須是公認的',
    'active_url'           => ':attribute 是無效的網址',
    'after'                => ':attribute 必須在 :date 之後',
    'after_or_equal'       => ':attribute 必須在 :date 或 :date 之後',
    'alpha'                => ':attribute 只能是字母',
    'alpha_dash'           => ':attribute 只能是字母、數字或-',
    'alpha_num'            => ':attribute 只能是字母或數字',
    'array'                => ':attribute 必須是陣列格式',
    'before'               => ':attribute 必須在 :date 之前',
    'before_or_equal'      => ':attribute 必須在 :date 或 :date 之前',
    'between'              => [
        'numeric' => ':attribute 必須介於:min 和 :max.',
        'file'    => ':attribute 必須介於:min 和 :max kb',
        'string'  => ':attribute 必須介於:min 和 :max 個字元',
        'array'   => ':attribute 必須介於 :min 和 :max',
    ],
    'boolean'              => ':attribute 必須是布林值',
    'confirmed'            => ':attribute 無法通過此確認',
    'date'                 => ':attribute 是無效的',
    'date_format'          => ':attribute 不符合 :format.',
    'different'            => ':attribute 和 :other 必須是不同的',
    'digits'               => ':attribute 必須是 :digits',
    'digits_between'       => ':attribute 必須介於:min 和 :max',
    'dimensions'           => ':attribute 無效的格式',
    'distinct'             => ':attribute 重複的 value',
    'email'                => ':attribute 必須是有效的 Email',
    'exists'               => ':attribute 已經存在',
    'file'                 => ':attribute 必須是檔案',
    'filled'               => ':attribute 不能為空',
    'image'                => ':attribute 必須是圖片檔',
    'in'                   => ':attribute 無效',
    'in_array'             => ':attribute 不存在 :other.',
    'integer'              => ':attribute 必須是整數',
    'ip'                   => ':attribute 必須是 IP',
    'ipv4'                 => ':attribute 必須是 IPv4',
    'ipv6'                 => ':attribute 必須是 IPv6',
    'json'                 => ':attribute 必須是 JSON',
    'max'                  => [
        'numeric' => ':attribute 不能大於 :max',
        'file'    => ':attribute 不能大於 :max kb',
        'string'  => ':attribute 不能大於 :max',
        'array'   => ':attribute 不能超過 :max',
    ],
    'mimes'                => ':attribute 必須是 :values 格式',
    'mimetypes'            => ':attribute 必須是 :values 格式',
    'min'                  => [
        'numeric' => ':attribute 不能小於 :min',
        'file'    => ':attribute 不能小於 :min kb',
        'string'  => ':attribute 不能小於 :min',
        'array'   => ':attribute 不能小於 :min',
    ],
    'not_in'               => ':attribute 無效',
    'numeric'              => ':attribute 必須是數字',
    'present'              => ':attribute 必須是 present.',
    'regex'                => ':attribute 文字格式未通過驗證',
    'required'             => ':attribute 是必須的',
    'required_if'          => ':attribute 是必須的，當 :other = :value.',
    'required_unless'      => ':attribute 是必須的，除非 :other 存在於 :values.',
    'required_with'        => ':attribute 是必須的，當 :values is  present.',
    'required_with_all'    => ':attribute 是必須的，當 :values is present.',
    'required_without'     => ':attribute 是必須的，當 :values is not present.',
    'required_without_all' => ':attribute 是必須的，當沒有 :values are present.',
    'same'                 => ':attribute 和 :other 必須是相同',
    'size'                 => [
        'numeric' => ':attribute 必須是 :size',
        'file'    => ':attribute 必須是 :size kb',
        'string'  => ':attribute 必須是 :size',
        'array'   => ':attribute 必須是包含 :size',
    ],
    'string'               => ':attribute 必須是 string.',
    'timezone'             => ':attribute 必須是 valid zone.',
    'unique'               => ':attribute 已經存在',
    'uploaded'             => ':attribute 上傳失敗',
    'url'                  => ':attribute 格式錯誤',

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
        'attribute-name' => [
            'rule-name' => 'custom-message',
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

    'attributes' => [],

];
