<?php

namespace App\Http\Controllers\Api;

use App\Models\Posts;
use App\Services\PageService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CardBuilder;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    protected $tables;
    protected $service;
    const LANG = 1;
    public function __construct() {
        $this->tables = config('tables');
        $this->service = new PageService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = ['body' => [], 'confirm' => 'error'];
        return response()->json($response);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function main(Request $request) {
        return response()->json($this->service->main($request->input('geo')));
    }
    public function bestCasinos(Request $request) {
        return response()->json($this->service->bestCasinos($request->input('geo')));
    }
    public function shares(){
        return response()->json($this->service->shares());
    }
    public function bonuses(Request $request){
        return response()->json($this->service->bonuses($request->input('geo')));
    }
    public function games(){
        return response()->json($this->service->games());
    }
    public function search(Request $request){
        $searchWord = $request->has('search_word') ? $request->input('search_word') : '';
        $lang = $request->has('lang') ? $request->input('lang') : self::LANG;
        return response()->json($this->service->search($searchWord, $lang));
    }
    public function news(){
        return response()->json($this->service->news());
    }
    public function siteMap(){
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $priority = 0.9;
        $data = [];
        $static_page = DB::table($this->tables['PAGES'])
                           ->where('status',  'public')
                           ->where('lang',  self::LANG)
                           ->get();

        foreach ($static_page as $item) {
            $data[] = [
                'url'        => $item->permalink === '/' ? $item->permalink : '/'.$item->permalink,
                'lastmod'    => $item->update_at,
                'changefreq' => $item->permalink === '/' ? 'weekly' : 'yearly',
                'priority'   => $item->permalink === '/' ? 1 : $priority
            ];
        }
        $arr_db = [
            ['db' => $this->tables['BONUS_CATEGORY'], 'slug' => 'bonuses'],
            ['db' => $this->tables['CASINO'], 'slug' => 'casino'],
            ['db' => $this->tables['CASINO_CATEGORY'], 'slug' => 'casinos'],
            ['db' => $this->tables['GAME'], 'slug' => 'game'],
            ['db' => $this->tables['VENDOR'], 'slug' => 'vendor'],
            ['db' => $this->tables['NEWS'], 'slug' => 'news'],
            ['db' => $this->tables['BONUS'], 'slug' => 'bonus'],
        ];
        foreach ($arr_db as $item) {
            $posts = DB::table($item['db'])
                ->where('status',  'public')
                ->where('lang',  self::LANG)
                ->get();
            foreach ($posts as $post) {
                $data[] = [
                    'url'        => '/'.$item['slug'].'/'.$post->permalink,
                    'lastmod'    => $post->update_at,
                    'changefreq' => 'monthly',
                    'priority'   => 0.8
                ];
            }
        }
        $response['body']['posts'] = $data;
        return response()->json($response);
    }
    public function bonusRoomCasino() {
        return response()->json($this->service->bonusRoomCasino());
    }
    public function default() {
        $path = request()->path();
        $slug = str_replace('api/pages/', '', $path);
        return response()->json($this->service->default($slug));
    }
}
