<?php
namespace App\Http\Controllers\Api;

use App\Services\AuthorService;

class AuthorController extends PostController {
    public function __construct() {
        $this->service = new AuthorService();
    }
}
