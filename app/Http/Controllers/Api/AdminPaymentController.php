<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminPaymentService;

class AdminPaymentController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminPaymentService();
    }
}