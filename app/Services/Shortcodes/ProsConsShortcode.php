<?php
namespace App\Services\Shortcodes;

class ProsConsShortcode
{
    public static function render($params, $data)
    {
        $html = '';
        $langs = config('constants.LANG');
        $translates = config('translates');
        $prosList = [];
        $consList = [];
        if (!empty($data->pros)) {
            $prosList = json_decode($data->pros, true);
            if(!empty($prosList)) {
                $html .= "<div class='pros'>
                <div class='pros_cons_title pros_icon'>{$translates['PROS'][$langs[$data->lang]]}</div>
                <ul class='pros_list'>";
                foreach ($prosList as $item) $html .= "<li>{$item}</li>";
                $html .= "</ul></div>";
            }
        }
        if (!empty($data->cons)) {
            $consList = json_decode($data->cons, true);
            if(!empty($consList)) {
                $html .= "<div class='cons'>
                <div class='pros_cons_title cons_icon'>{$translates['CONS'][$langs[$data->lang]]}</div>
                <ul class='cons_list'>";
                foreach ($consList as $item) $html .= "<li>{$item}</li>";
                $html .= "</ul></div>";
            }
        }
        return empty($prosList) && empty($consList) ? '' : "<div class='pros_cons'>{$html}</div>";
    }
}
