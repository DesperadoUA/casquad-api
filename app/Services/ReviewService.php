<?php
namespace App\Services;
use App\Models\Review;

class ReviewService {
    protected $response;
    protected $model;
    function __construct() {
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->model = new Review();
    }
    public function show($id) {
        $data = $this->model->getPostById($id);
        if(!$data->isEmpty()) {
            $this->response['confirm'] = 'ok';
            $this->response['body'] = $data;
        }
        return $this->response;
    }
}