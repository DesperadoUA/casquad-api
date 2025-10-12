<?php
namespace App\Http\Controllers\Api;

use App\Services\ArticleService;

class ArticleController extends PostController {
    public function __construct() {
        $this->service = new ArticleService();
    }
    public function reviews($id) {
        $request = request();
        return response()->json($this->service->reviews($id, $request->query('sort'), $request->query('order')));
    }
}
