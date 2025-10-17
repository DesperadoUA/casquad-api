<?php
namespace App\Services;

use App\Models\Posts;
use App\Models\Cash;

class AdminFunnelService extends AdminPostService {
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.FUNNEL');
        $this->configTables = [
            'table' => $this->tables['FUNNEL'],
            'table_meta' => $this->tables['FUNNEL_META'],
            'table_category' => $this->tables['FUNNEL_CATEGORY'],
            'table_relative' => $this->tables['FUNNEL_CATEGORY_RELATIVE'],
        ];
    }
    public function adminShow($id) {
        $post = new Posts(['table' => $this->tables['FUNNEL'], 'table_meta' => $this->tables['FUNNEL_META']]);
        $data = $post->getPostById($id);
        if (!empty(count($data))) {
            $this->response['body'] = $this->serialize->adminSerialize($data[0], $this->shemas);
            $this->response['body']['category'] = self::relativeCategoryPost($id, $this->tables['FUNNEL'], 
                                                                                  $this->tables['FUNNEL_CATEGORY'], 
                                                                                  $this->tables['FUNNEL_CATEGORY_RELATIVE']);                                                                     
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function update($data) {
        $data_save = $this->serialize->validateUpdate($data, $this->tables['FUNNEL'], $this->tables['FUNNEL_META']);
        $post = new Posts(['table' => $this->tables['FUNNEL'], 'table_meta' => $this->tables['FUNNEL_META']]);
        $post->updateById($data['id'], $data_save);

        $data_meta = $this->serialize->validateMetaSave($data, $this->shemas);
        $post->updateMetaById($data['id'], $data_meta);
        self::updateCategory($data['id'], $data['category'], $this->tables['FUNNEL'], 
                                                             $this->tables['FUNNEL_CATEGORY'], 
                                                             $this->tables['FUNNEL_CATEGORY_RELATIVE']);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
}