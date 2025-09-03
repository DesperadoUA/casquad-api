<?php
namespace App\Http\Controllers\Api;

use App\Services\VendorService;

class VendorController extends PostController {
    public function __construct() {
        $this->service = new VendorService();
    }
    public function show($id) {
        $request = request();
        return response()->json($this->service->show($id, $request->input('geo')));
    }
}