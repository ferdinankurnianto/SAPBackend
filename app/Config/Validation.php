<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    public $transaction = [
        'customer_name' => 'required',
        'product_name' => 'required',
        'qty_out' => 'required|is_natural_no_zero',
    ];

    public $transaction_errors = [
        'customer_name' => [
			'required' => 'Customer Name is required.'
		],
        'product_name' => [
			'required' => 'Product Name is required.'
		],
        'qty_out' => [
			'required' => 'Quantity is required.',
            'is_natural_no_zero' => 'Quantity must be number greater than 0.',
		],
    ];
}
