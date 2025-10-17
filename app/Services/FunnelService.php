<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\Models\Cash;
use App\CardBuilder\AuthorCardBuilder;
use App\CardBuilder\CasinoCardBuilder;
use App\CardBuilder\GameCardBuilder;
use App\CardBuilder\NewsCardBuilder;
use App\Models\Review;
use App\CardBuilder\ReviewCardBuilder;

class FunnelService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.FUNNEL');
        $this->configTables =  [
            'table' => $this->tables['FUNNEL'],
            'table_meta' => $this->tables['FUNNEL_META'],
            'table_category' => $this->tables['FUNNEL_CATEGORY'],
            'table_relative' => $this->tables['FUNNEL_CATEGORY_RELATIVE']
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
