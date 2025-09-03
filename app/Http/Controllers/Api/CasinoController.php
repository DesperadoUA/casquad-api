<?php
namespace App\Http\Controllers\Api;

use App\Services\CasinoService;

class CasinoController extends PostController {
    public function __construct() {
        $this->service = new CasinoService();
    }
    public function show($id) {
        $request = request();
        return response()->json($this->service->show($id, $request->input('geo')));
    }
    public function category($id) {
        $request = request();
        return response()->json($this->service->categoryWithGeo($id, $request->input('geo')));
    }
}