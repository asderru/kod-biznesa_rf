<?php
    
    namespace frontend\extensions\services\search;
    
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Product\Value;
    use core\edit\entities\Shop\Razdel;
    use Elasticsearch\Client;
    use stdClass;
    use yii\helpers\ArrayHelper;
    
    class ProductIndexer
    {
        private Client $client;
        
        public function __construct(Client $client)
        {
            $this->client = $client;
        }
        
        public function clear(): void
        {
            $this->client->deleteByQuery([
                'index' => 'shop',
                'type'  => 'products',
                'body'  => [
                    'query' => [
                        'match_all' => new stdClass(),
                    ],
                ],
            ]);
        }
        
        public function index(Product $product): void
        {
            $this->client->index([
                'index' => 'shop',
                'type'  => 'products',
                'id'    => $product->id,
                'body'  => [
                    'id'          => $product->id,
                    'name'        => $product->name,
                    'description' => strip_tags($product->description),
                    'price'       => $product->price_new,
                    'rating'      => $product->rating,
                    'brand'       => $product->brand_id,
                    'categories'  => ArrayHelper::merge(
                        [$product->razdel->id],
                        ArrayHelper::getColumn($product->razdel->parents, 'id'),
                        ArrayHelper::getColumn($product->categories, 'id'),
                        array_reduce(
                            array_map(static function (Razdel $razdel) {
                                return ArrayHelper::getColumn($razdel->parents, 'id');
                            }, $product->categories), 'array_merge', [],
                        ),
                    ),
                    'tags'        => ArrayHelper::getColumn($product->tagAssignments, 'tag_id'),
                    'values'      => array_map(static function (Value $value) {
                        return [
                            'characteristic' => $value->characteristic_id,
                            'value_string'   => $value->value,
                            'value_int'      => (int)$value->value,
                        ];
                    }, $product->values),
                ],
            ]);
        }
        
        public function remove(Product $product): void
        {
            $this->client->delete([
                'index' => 'shop',
                'type'  => 'products',
                'id'    => $product->id,
            ]);
        }
    }
