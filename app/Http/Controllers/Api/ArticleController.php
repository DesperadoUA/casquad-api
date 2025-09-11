<?php
namespace App\Http\Controllers\Api;

use App\Services\ArticleService;

class ArticleController extends PostController {
    public function __construct() {
        $this->service = new ArticleService();
    }
}
