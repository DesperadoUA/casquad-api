<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ReviewService;

class ReviewController extends Controller {
    protected $service;
    public function __construct() {
        $this->service = new ReviewService();
    }
    public function show($id) {
        return response()->json($this->service->show($id));
    }
}
