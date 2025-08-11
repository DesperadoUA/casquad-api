<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminAuthorService;

class AdminAuthorController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminAuthorService();
    }
}
