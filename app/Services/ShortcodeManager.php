<?php
namespace App\Services;

class ShortcodeManager
{
    protected static $shortcodes = [];
    public static function add($name, callable $callback)
    {
        self::$shortcodes[$name] = $callback;
    }

   public static function parse($content, $data)
    {
        return preg_replace_callback('/\[(\w+)(.*?)\]/', function ($matches) use ($data) {
            $name = $matches[1];
            $params = [];
            if (preg_match_all('/(\w+)="([^"]+)"/', $matches[2], $attrMatches, PREG_SET_ORDER)) {
                foreach ($attrMatches as $attr) {
                    $params[$attr[1]] = $attr[2];
                }
            }
            if (isset(self::$shortcodes[$name])) {
                return call_user_func(self::$shortcodes[$name], $params, $data);
            }
            return $matches[0];
    }, $content);
    }
}
