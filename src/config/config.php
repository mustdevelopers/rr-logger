<?php

return [
    'retention_days' => env('RRLOGGER_RETENTION_DAYS', 30),
    'hidden_fields' => env('RRLOGGER_HIDDEN_FIELDS', 'password,pin,new_pin'),
    'table_name' => env('RRLOGGER_TABLE_NAME', 'rrloggers'),
    'max_content_length' => env('RRLOGGER_MAX_CONTENT_LENGTH', 65535),
];
