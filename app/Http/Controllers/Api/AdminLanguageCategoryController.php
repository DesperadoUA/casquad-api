<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCategoryService;

class AdminLanguageCategoryController extends AdminCategoryController {
    public function __construct() {
        parent::__construct();
        $this->service = new AdminCategoryService([
            'table' => $this->tables['LANGUAGE'],
            'table_meta' => $this->tables['LANGUAGE_META'],
            'table_category' => $this->tables['LANGUAGE_CATEGORY'],
            'table_relative' => $this->tables['LANGUAGE_CATEGORY_RELATIVE'],
        ]);
    }
}