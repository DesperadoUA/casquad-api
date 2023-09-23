<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminLanguageService;

class AdminLanguageController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminLanguageService();
    }
}