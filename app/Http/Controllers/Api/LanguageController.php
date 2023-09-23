<?php
namespace App\Http\Controllers\Api;

use App\Services\LanguageService;

class LanguageController extends PostController {
    public function __construct() {
        $this->service = new LanguageService();
    }
}