<?php
namespace App\Http\Controllers\Api;

use App\Services\FunnelService;

class FunnelController extends PostController {
    public function __construct() {
        $this->service = new FunnelService();
    }
}
