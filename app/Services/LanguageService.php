<?php
namespace App\Services;
use App\Models\Posts;
use App\Services\FrontBaseService;
use App\Models\Cash;
use App\CardBuilder\LanguageCardBuilder;

class LanguageService extends FrontBaseService {
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.LANGUAGE');
        $this->configTables =  [
            'table' => $this->tables['LANGUAGE'],
            'table_meta' => $this->tables['LANGUAGE_META'],
            'table_category' => $this->tables['LANGUAGE_CATEGORY'],
            'table_relative' => $this->tables['LANGUAGE_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new LanguageCardBuilder();
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