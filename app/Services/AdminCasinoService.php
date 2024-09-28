<?php
namespace App\Services;

use App\Models\Posts;
use App\Models\Cash;

class AdminCasinoService extends AdminPostService {
    function __construct() {
        parent::__construct();
        $this->shemas = config('shemas.CASINO');
        $this->configTables = [
            'table' => $this->tables['CASINO'],
            'table_meta' => $this->tables['CASINO_META'],
            'table_category' => $this->tables['CASINO_CATEGORY'],
            'table_relative' => $this->tables['CASINO_CATEGORY_RELATIVE'],
        ];
    }
    public function adminShow($id) {
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $data = $post->getPostById($id);
        if (!empty(count($data))) {
            $this->response['body'] = $this->serialize->adminSerialize($data[0], $this->shemas);
            $this->response['body']['category'] = self::relativeCategoryPost($id, $this->tables['CASINO'], 
                                                                                  $this->tables['CASINO_CATEGORY'], 
                                                                                  $this->tables['CASINO_CATEGORY_RELATIVE']);
            $this->response['body']['casino_vendor'] = self::relativePostPost($id, $this->tables['CASINO'], 
                                                                                   $this->tables['VENDOR'], 
                                                                                   $this->tables['CASINO_VENDOR_RELATIVE']);
            $this->response['body']['casino_payment'] = self::relativePostPost($id, $this->tables['CASINO'], 
                                                                                   $this->tables['PAYMENT'], 
                                                                                   $this->tables['CASINO_PAYMENT_RELATIVE']);
            $this->response['body']['casino_deposit'] = self::relativePostPost($id, $this->tables['CASINO'], 
                                                                                   $this->tables['PAYMENT'], 
                                                                                   $this->tables['CASINO_DEPOSIT_RELATIVE']);
            $this->response['body']['casino_currency'] = self::relativePostPost($id, $this->tables['CASINO'], 
                                                                                   $this->tables['CURRENCY'], 
                                                                                   $this->tables['CASINO_CURRENCY_RELATIVE']);
            $this->response['body']['casino_language'] = self::relativePostPost($id, $this->tables['CASINO'], 
                                                                                   $this->tables['LANGUAGE'], 
                                                                                   $this->tables['CASINO_LANGUAGE_RELATIVE']);
            $this->response['body']['casino_casino'] = self::relativePostPost($id, $this->tables['CASINO'], 
                                                                                   $this->tables['CASINO'], 
                                                                                   $this->tables['CASINO_CASINO_RELATIVE']);
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function update($data) {
        $data_save = $this->serialize->validateUpdate($data, $this->tables['CASINO'], $this->tables['CASINO_META']);
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $post->updateById($data['id'], $data_save);

        $data_meta = $this->serialize->validateMetaSave($data, $this->shemas);
        $post->updateMetaById($data['id'], $data_meta);
        self::updateCategory($data['id'], $data['category'], $this->tables['CASINO'], 
                                                             $this->tables['CASINO_CATEGORY'], 
                                                             $this->tables['CASINO_CATEGORY_RELATIVE']);
        self::updatePostPost($data['id'], $data['casino_vendor'], $this->tables['CASINO'], 
                                                                  $this->tables['VENDOR'], 
                                                                  $this->tables['CASINO_VENDOR_RELATIVE']);
        self::updatePostPost($data['id'], $data['casino_payment'], $this->tables['CASINO'], 
                                                                  $this->tables['PAYMENT'], 
                                                                  $this->tables['CASINO_PAYMENT_RELATIVE']);
        self::updatePostPost($data['id'], $data['casino_deposit'], $this->tables['CASINO'], 
                                                                  $this->tables['PAYMENT'], 
                                                                  $this->tables['CASINO_DEPOSIT_RELATIVE']);
        self::updatePostPost($data['id'], $data['casino_currency'], $this->tables['CASINO'], 
                                                                  $this->tables['CURRENCY'], 
                                                                  $this->tables['CASINO_CURRENCY_RELATIVE']);
        self::updatePostPost($data['id'], $data['casino_language'], $this->tables['CASINO'], 
                                                                  $this->tables['LANGUAGE'], 
                                                                  $this->tables['CASINO_LANGUAGE_RELATIVE']);
        self::updatePostPost($data['id'], $data['casino_casino'], $this->tables['CASINO'], 
                                                                  $this->tables['CASINO'], 
                                                                  $this->tables['CASINO_CASINO_RELATIVE']);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
}