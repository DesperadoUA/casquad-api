<?php
namespace App\Http\Controllers\Api;

use App\Services\CurrencyService;

class CurrencyController extends PostController {
    public function __construct() {
        $this->service = new CurrencyService();
    }
}