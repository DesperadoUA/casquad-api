<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCategoryService;

class AdminCurrencyCategoryController extends AdminCategoryController {
    public function __construct() {
        parent::__construct();
        $this->service = new AdminCategoryService([
            'table' => $this->tables['CURRENCY'],
            'table_meta' => $this->tables['CURRENCY_META'],
            'table_category' => $this->tables['CURRENCY_CATEGORY'],
            'table_relative' => $this->tables['CURRENCY_CATEGORY_RELATIVE'],
        ]);
    }
}