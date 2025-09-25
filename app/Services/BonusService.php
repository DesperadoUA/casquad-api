<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\Models\Cash;
use App\CardBuilder\BonusCardBuilder;
use App\CardBuilder\CasinoCardBuilder;
use App\Models\Category;
use App\CardBuilder\AuthorCardBuilder;

class BonusService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.BONUS');
        $this->configTables =  [
            'table' => $this->tables['BONUS'],
            'table_meta' => $this->tables['BONUS_META'],
            'table_category' => $this->tables['BONUS_CATEGORY'],
            'table_relative' => $this->tables['BONUS_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new BonusCardBuilder();
    }
    public function show($id, $geo) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);
            $casinoModel = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
            $casinoCardBuilder = new CasinoCardBuilder();
            $casinoIds = Relative::getRelativeByPostId($this->tables['BONUS_CASINO_RELATIVE'], $data[0]->id);
            $casino = $casinoModel->getPublicPostsByArrId($casinoIds);
            $this->response['body']['casino'] = $casino->isEmpty() ? [] : $casinoCardBuilder->bonusCard($casino)[0];
            $this->response['body']['bonuses'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['BONUS_CASINO_RELATIVE'], $casino[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new BonusCardBuilder();
                $Model = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
                $publicPosts = $Model->getPublicPostsByArrIdAndGeo($arr_posts, $geo);
                $filters = [];
                foreach($publicPosts as $item) if($item->id !== $data[0]->id) $filters[] = $item;
                $this->response['body']['bonuses'] = $CardBuilder->main($filters);
            }
            if(!empty($this->response['body']['characters'])) {
                $bonus_characters = [];
                foreach($this->response['body']['characters'] as $item) {
                    $bonus_item_characters = [
                        'value' => $item['value'],
                        'child' => []
                    ];
                    $child = [];
                    foreach($item['child'] as $itemChild) {
                        $child[] = [
                            'value_1' => $itemChild['value_1'],
                            'value_2' => explode(',', $itemChild['value_2'])
                        ];
                    }
                    $bonus_item_characters['child'] = $child;
                    $bonus_characters[] = $bonus_item_characters;
                }
                $this->response['body']['characters'] = $bonus_characters;
            }

            $this->response['body']['authors'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['BONUS_AUTHOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new AuthorCardBuilder();
                $Model = new Posts(['table' => $this->tables['AUTHOR'], 'table_meta' => $this->tables['AUTHOR_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $posts = $CardBuilder->summary($publicPosts);
                $this->response['body']['authors'] = $posts;
            }
            $ref_list = [];
            foreach($this->response['body']['ref'] as $refItem) {
                $ref_list[$refItem['value_2']] = $refItem['value_1'];
            }
            $this->response['body']['ref'] = $ref_list;
            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;
    }
    public function categoryWithGeo($id, $geo) {
        $category = new Category($this->configTables);
        $data = $category->getPublicPostByUrl($id);
        if(!$data->isEmpty()) {
            $this->response['body'] = $this->categorySerialize->frontSerialize($data[0]);

            $this->response['body']['posts'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->configTables['table_relative'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
                $this->response['body']['posts'] = $this->cardBuilder->main($post->getPublicPostsByArrIdAndGeo($arr_posts, $geo));
            }

            $this->response['body']['authors'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['BONUS_AUTHOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new AuthorCardBuilder();
                $Model = new Posts(['table' => $this->tables['AUTHOR'], 'table_meta' => $this->tables['AUTHOR_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $posts = $CardBuilder->main($publicPosts);
                $this->response['body']['authors'] = $posts;
            }

            $this->response['confirm'] = 'ok';
            Cash::store(url()->full(), json_encode($this->response));
        }
        return $this->response;    
    }
}