<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\Models\Cash;

class AuthorService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.AUTHOR');
        $this->configTables =  [
            'table' => $this->tables['AUTHOR'],
            'table_meta' => $this->tables['AUTHOR_META'],
            'table_category' => $this->tables['AUTHOR_CATEGORY'],
            'table_relative' => $this->tables['AUTHOR_CATEGORY_RELATIVE']
        ];
    }
    public function show($id) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
}
