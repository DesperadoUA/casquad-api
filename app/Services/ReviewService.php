<?php
namespace App\Services;
use App\Models\Review;
use App\Serialize\ReviewSerialize;

class ReviewService {
    protected $response;
    protected $model;
    protected $serialize;
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
    public function store($data) {
        $data_save = ReviewSerialize::validateInsert($data);
        $this->response['insert_id'] = $this->model->insert($data_save);
        $this->response['confirm'] = 'ok';
        return $this->response;
    }
}