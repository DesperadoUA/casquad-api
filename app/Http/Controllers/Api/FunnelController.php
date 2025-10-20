<?php
namespace App\Http\Controllers\Api;

use App\Services\FunnelService;

class FunnelController extends PostController {
    public function __construct() {
        $this->service = new FunnelService();
    }
    public function reviews($id) {
        $request = request();
        return response()->json($this->service->reviews($id, $request->query('sort'), $request->query('order')));
    }
}
