<?php
namespace App\Services;

use App\Models\Posts;
use App\Models\Cash;

class AdminBonusService extends AdminPostService {
    function __construct() {
        parent::__construct();
        $this->shemas = config('shemas.BONUS');
        $this->configTables = [
            'table' => $this->tables['BONUS'],
            'table_meta' => $this->tables['BONUS_META'],
            'table_category' => $this->tables['BONUS_CATEGORY'],
            'table_relative' => $this->tables['BONUS_CATEGORY_RELATIVE'],
        ];
    }
    public function adminShow($id) {
        $post = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
        $data = $post->getPostById($id);
        if (!empty(count($data))) {
            $this->response['body'] = $this->serialize->adminSerialize($data[0], $this->shemas);
            $this->response['body']['category'] = self::relativeCategoryPost($id, $this->tables['BONUS'], 
                                                                                  $this->tables['BONUS_CATEGORY'], 
                                                                                  $this->tables['BONUS_CATEGORY_RELATIVE']);
            
            $this->response['body']['bonus_casino'] = self::relativePostPost($id, $this->tables['BONUS'], 
                                                                                  $this->tables['CASINO'], 
                                                                                  $this->tables['BONUS_CASINO_RELATIVE']);
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function update($data) {
        $data_save =  $this->serialize->validateUpdate($data, $this->tables['BONUS'], $this->tables['BONUS_META']);
        $post = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
        $post->updateById($data['id'], $data_save);

        $data_meta = $this->serialize->validateMetaSave($data, $this->shemas);
        $post->updateMetaById($data['id'], $data_meta);
        self::updateCategory($data['id'], $data['category'], $this->tables['BONUS'], 
                                                             $this->tables['BONUS_CATEGORY'], 
                                                             $this->tables['BONUS_CATEGORY_RELATIVE']);
                                                             
        self::updatePostPost($data['id'], $data['bonus_casino'], $this->tables['BONUS'], 
                                                                 $this->tables['CASINO'], 
                                                                 $this->tables['BONUS_CASINO_RELATIVE']);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
}