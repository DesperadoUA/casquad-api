<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\CardBuilder\GameCardBuilder;
use App\CardBuilder\VendorCardBuilder;
use App\CardBuilder\CasinoCardBuilder;
use App\Models\Cash;

class GameService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.GAME');
        $this->configTables =  [
            'table' => $this->tables['GAME'],
            'table_meta' => $this->tables['GAME_META'],
            'table_category' => $this->tables['GAME_CATEGORY'],
            'table_relative' => $this->tables['GAME_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new GameCardBuilder();
    }
    public function show($id) {
        $post = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);

            $this->response['body']['vendor'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['GAME_VENDOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new VendorCardBuilder();
                $Model = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['vendor'] = $CardBuilder->filterCard($publicPosts);
            }

            $this->response['body']['casinos'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['GAME_CASINO_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new CasinoCardBuilder();
                $Model = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['casinos'] = $CardBuilder->sliderCard($publicPosts);
            }

            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
}