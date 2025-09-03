<?php
namespace App\Services;

use App\Models\Review;
use App\Models\Posts;
use App\Models\Cash;

class AdminReviewService {
    protected $response;
    protected $model;
    protected $tables;
    protected $configPostTypes;
    function __construct() {
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->model = new Review();
        $this->tables = config('tables');
        $this->configPostTypes = [
            'game' => [
                'table' => $this->tables['GAME'],
                'table_meta' => $this->tables['GAME_META']
            ],
            'casino' => [
                'table' => $this->tables['CASINO'],
                'table_meta' => $this->tables['CASINO_META']
            ],
            'vendor' => [
                'table' => $this->tables['VENDOR'],
                'table_meta' => $this->tables['VENDOR_META']
            ]
        ];
    }
    public function adminIndex($settings) {
        $data = $this->model->getPosts($settings);
        $this->response['body'] = $data;
        $this->response['confirm'] = 'ok';
        $this->response['total'] = $this->model->getTotalCountByLang($settings['lang']);
        $this->response['lang'] = config('constants.LANG')[$settings['lang']];
        return $this->response;
    }
    public function store($data) {
        //$data_save = $this->serialize->validateInsert($data, $this->configTables['table'], $this->configTables['table_meta']);
        //$data_meta = $this->serialize->validateMetaSave($data, $this->shemas);
        //$post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        //$this->response['insert_id'] = $post->insert($data_save, $data_meta);
        //$this->response['confirm'] = 'ok';
        return $this->response;
    }
    public function delete($data) {
        $this->model->deleteById($data);
        $this->model->deleteByParentReviewId($data);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
    public function adminShow($id) {
        $data = $this->model->getPostById($id);
        if(!$data->isEmpty()) {
            $this->response['confirm'] = 'ok';
            $postModel = new Posts(['table' => $this->configPostTypes[$data[0]->post_type]['table'], 'table_meta' => $this->configPostTypes[$data[0]->post_type]['table_meta']]);
            $this->response['body'] = (array) $data[0];
            $parent_post = $postModel->getPostById($data[0]->parent_post_id);
            $this->response['body']['parent_post'] = "{$parent_post[0]->title} [{$parent_post[0]->post_type}]"; 
        }
        return $this->response;
    }
    public function update($data) {
        $this->response['confirm'] = 'ok';
        $post = [
            'content' => $data['content'],
            'create_at' => $data['create_at'],
            'email' => $data['email'],
            'name' => $data['name'],
            'status' => $data['status'],
            'update_at' => $data['update_at']
        ];
        $this->model->updateById($data['id'], $post);
        Cash::deleteAll();
        return $this->response;
    }
}
