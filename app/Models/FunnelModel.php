<?php
namespace App\Models;

class FunnelModel extends Posts {
    public function __construct(array $attributes = [])
    {
        $defaults = [
            'table' => 'funnels',
            'table_meta' => 'funnel_meta',
        ];
        parent::__construct(array_merge($defaults, $attributes));
    }
}