<?php
namespace App\Http\Controllers\Api;

use App\Services\AuthorService;

class AuthorController extends PostController {
    public function __construct() {
        $this->service = new AuthorService();
    }
    public function relativeArticles($id) {
        $request = request();
        $offset = $request->input('offset', 0);   
        $limit  = $request->input('limit', 10);
        return response()->json(
            $this->service->relativeArticles($id, $offset, $limit)
        );
    }
    public function relativeCasinos($id) {
        $request = request();
        $offset = $request->input('offset', 0);   
        $limit  = $request->input('limit', 10);
        return response()->json(
            $this->service->relativeCasinos($id, $offset, $limit)
        );
    }
}
