<?php

namespace App\Services\Pipelines\Queries\Query2;

class WhereLike implements IQuery
{
    public $column;
    public $content;

    public function __construct($column, $content = '')
    {
        $this->column = $column;
        $this->content = $content;
    }

    public function handle($query, \Closure $next)
    {
        if ($this->content) {
            $query = $query->where($this->column, 'like', "%{$this->content}%");
        }

        return $next($query);
    }
}
