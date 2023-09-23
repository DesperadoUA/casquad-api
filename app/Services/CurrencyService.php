<?php
namespace App\Services;
use App\Models\Posts;
use App\Services\FrontBaseService;
use App\Models\Cash;
use App\CardBuilder\CurrencyCardBuilder;

class CurrencyService extends FrontBaseService {
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.CURRENCY');
        $this->configTables =  [
            'table' => $this->tables['CURRENCY'],
            'table_meta' => $this->tables['CURRENCY_META'],
            'table_category' => $this->tables['CURRENCY_CATEGORY'],
            'table_relative' => $this->tables['CURRENCY_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new CurrencyCardBuilder();
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