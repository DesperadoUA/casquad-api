<?php
namespace App\Http\Controllers\Api;

use App\Services\BonusService;
use Illuminate\Http\Request;

class BonusController extends PostController {
    public function __construct() {
        $this->service = new BonusService();
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