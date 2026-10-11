<?php

declare(strict_types=1);

return [
    'currency_code' => 'BDT',
    'currency_word' => 'Taka',
    'minor_currency_word' => 'Paisa',
    'currency_symbol' => '৳',
    'currency_position' => 'before',
    'default_width_mm' => 203.00,
    'default_height_mm' => 90.00,
    'upload_paths' => [
        'bank_logos' => 'uploads/bank-logos',
        'cheque_backgrounds' => 'uploads/cheque-backgrounds',
        'company_logos' => 'uploads/company-logos',
    ],
    'max_upload_size_kb' => 4096,
    'demo_mode' => env('DEMO_MODE', false),
    'field_labels' => [
        'payee' => 'Pay to',
        'amount_digits' => 'Amount',
        'amount_words_1' => 'Amount in words (line 1)',
        'amount_words_2' => 'Amount in words (line 2)',
        'date' => 'Date',
        'memo' => 'Memo / Reference',
    ],
];
