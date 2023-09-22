<?php
namespace App\Services;
use App\Models\Posts;
use App\Services\FrontBaseService;
use App\Models\Cash;
use App\CardBuilder\PaymentCardBuilder;

class PaymentService extends FrontBaseService {
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.PAYMENT');
        $this->configTables =  [
            'table' => $this->tables['PAYMENT'],
            'table_meta' => $this->tables['PAYMENT_META'],
            'table_category' => $this->tables['PAYMENT_CATEGORY'],
            'table_relative' => $this->tables['PAYMENT_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new PaymentCardBuilder();
    }
    public function show($id) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);

            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
}