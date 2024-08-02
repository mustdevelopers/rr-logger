<?php

return [
    'retention_days' => env('RRLOGGER_RETENTION_DAYS', 30),
    'hidden_fields' => explode(',', env('RRLOGGER_HIDDEN_FIELDS', 'password,pin,new_pin')),
];
