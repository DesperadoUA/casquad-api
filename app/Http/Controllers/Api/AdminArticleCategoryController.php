<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCategoryService;

class AdminArticleCategoryController extends AdminCategoryController {
    public function __construct() {
        parent::__construct();
        $this->service = new AdminCategoryService([
            'table' => $this->tables['ARTICLE'],
            'table_meta' => $this->tables['ARTICLE_META'],
            'table_category' => $this->tables['ARTICLE_CATEGORY'],
            'table_relative' => $this->tables['ARTICLE_CATEGORY_RELATIVE']
        ]);
    }
}
