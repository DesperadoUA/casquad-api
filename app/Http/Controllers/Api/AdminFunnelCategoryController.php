<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCategoryService;

class AdminFunnelCategoryController extends AdminCategoryController {
    public function __construct() {
        parent::__construct();
        $this->service = new AdminCategoryService([
            'table' => $this->tables['FUNNEL'],
            'table_meta' => $this->tables['FUNNEL_META'],
            'table_category' => $this->tables['FUNNEL_CATEGORY'],
            'table_relative' => $this->tables['FUNNEL_CATEGORY_RELATIVE'],
        ]);
    }
}