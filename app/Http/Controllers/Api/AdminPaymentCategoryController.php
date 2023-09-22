<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCategoryService;

class AdminPaymentCategoryController extends AdminCategoryController {
    public function __construct() {
        parent::__construct();
        $this->service = new AdminCategoryService([
            'table' => $this->tables['PAYMENT'],
            'table_meta' => $this->tables['PAYMENT_META'],
            'table_category' => $this->tables['PAYMENT_CATEGORY'],
            'table_relative' => $this->tables['PAYMENT_CATEGORY_RELATIVE'],
        ]);
    }
}