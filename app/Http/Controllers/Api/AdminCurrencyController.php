<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCurrencyService;

class AdminCurrencyController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminCurrencyService();
    }
}