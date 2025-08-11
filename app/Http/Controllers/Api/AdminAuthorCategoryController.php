<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCategoryService;

class AdminAuthorCategoryController extends AdminCategoryController {
    public function __construct() {
        parent::__construct();
        $this->service = new AdminCategoryService([
            'table' => $this->tables['AUTHOR'],
            'table_meta' => $this->tables['AUTHOR_META'],
            'table_category' => $this->tables['AUTHOR_CATEGORY'],
            'table_relative' => $this->tables['AUTHOR_CATEGORY_RELATIVE']
        ]);
    }
}
