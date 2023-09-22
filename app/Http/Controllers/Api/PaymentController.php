<?php
namespace App\Http\Controllers\Api;

use App\Services\PaymentService;

class PaymentController extends PostController {
    public function __construct() {
        $this->service = new PaymentService();
    }
}