<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminArticleService;

class AdminArticleController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminArticleService();
    }
}
