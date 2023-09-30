<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\CardBuilder\VendorCardBuilder;
use App\CardBuilder\PaymentCardBuilder;
use App\CardBuilder\CurrencyCardBuilder;
use App\CardBuilder\BonusCardBuilder;
use App\CardBuilder\LanguageCardBuilder;
use App\CardBuilder\CasinoCardBuilder;
use App\Models\Cash;

class CasinoService extends FrontBaseService {
    protected $response;
    protected $config;
    const MAIN_PAGE_LIMIT_CASINO = 10;
    const CATEGORY_LIMIT_CASINO = 1000;
    const CATEGORY_LIMIT_GAME = 1000;
    const SLUG = 'casino';
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.CASINO');
        $this->configTables =  [
            'table' => $this->tables['CASINO'],
            'table_meta' => $this->tables['CASINO_META'],
            'table_category' => $this->tables['CASINO_CATEGORY'],
            'table_relative' => $this->tables['CASINO_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new CasinoCardBuilder();
    }
    public function show($id) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);

            $this->response['body']['vendors'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_VENDOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new VendorCardBuilder();
                $Model = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['vendors'] = $CardBuilder->vendorCasino($publicPosts);
            }

            $this->response['body']['payments'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_PAYMENT_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new PaymentCardBuilder();
                $Model = new Posts(['table' => $this->tables['PAYMENT'], 'table_meta' => $this->tables['PAYMENT_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['payments'] = $CardBuilder->main($publicPosts);
            }

            $this->response['body']['currencies'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_CURRENCY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new CurrencyCardBuilder();
                $Model = new Posts(['table' => $this->tables['CURRENCY'], 'table_meta' => $this->tables['CURRENCY_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['currencies'] = $CardBuilder->main($publicPosts);
            }

            $this->response['body']['languages'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_LANGUAGE_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new LanguageCardBuilder();
                $Model = new Posts(['table' => $this->tables['LANGUAGE'], 'table_meta' => $this->tables['LANGUAGE_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['languages'] = $CardBuilder->main($publicPosts);
            }

            $this->response['body']['bonuses'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['BONUS_CASINO_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $CardBuilder = new BonusCardBuilder();
                $Model = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
                $publicPosts = $Model->getPublicPostsByArrId($arr_posts);
                $this->response['body']['bonuses'] = $CardBuilder->main($publicPosts);
            }

            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
}