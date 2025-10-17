<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminFunnelService;

class AdminFunnelController extends AdminPostController
{
    public function __construct() {
        $this->service = new AdminFunnelService();
    }
}