<?php

use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Pagination\LengthAwarePaginator;

class ExamplesPaginationQuery extends Query
{
    protected $attributes = [
        'name' => 'Examples with pagination',
    ];

    public function type()
    {
        return GraphQL::pagination(GraphQL::type('Example'));
    }

    public function args()
    {
        return [
            'take' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'page' => [
                'type' => Type::nonNull(Type::int()),
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $data = include(__DIR__.'/data.php');

        $take = $args['take'];
        $page = $args['page'] - 1;

        return new LengthAwarePaginator(
            collect($data)->slice($page * $take, $take), 
            count($data), 
            $take, 
            $page
        );
    }
}
