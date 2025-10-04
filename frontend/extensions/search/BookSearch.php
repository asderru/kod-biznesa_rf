<?php
    
    namespace frontend\extensions\search;
    
    use core\edit\entities\Library\Book;
    use core\edit\traits\SearchTrait;
    use Exception;
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use yii\data\Sort;
    use yii\helpers\ArrayHelper;
    
    /**
     * BookSearch represents the model behind the search form of
     * `core\edit\entities\Library\Book`.
     */
    class BookSearch extends Book
    {
        use SearchTrait;
        
        public static function depthAmountList(): array
        {
            return Book::getDepthAmount();
        }
        
        /**
         * {@inheritdoc}
         */
        public function rules(): array
        {
            return [
                [['id', 'site_id', 'user_id', 'author_id', 'color', 'updated_at', 'rating', 'status', 'tree', 'lft', 'rgt', 'depth', 'content_id', 'pageSize'], 'integer'],
                [['name', 'slug', 'photo', 'title', 'description', 'text'], 'safe'],
            ];
        }
        
        /**
         * {@inheritdoc}
         */
        public function scenarios(): array
        {
            // bypass scenarios() implementation in the parent class
            return Model::scenarios();
        }
        
        /**
         * Creates data provider instance with search query applied
         *
         * @param array    $params
         * @param int|null $authorId
         * @return ActiveDataProvider
         * @throws Exception
         */
        
        public function search(
            array    $params,
            int|null $authorId = null,
            int|null $siteId = null,
        ): ActiveDataProvider
        {
            $sort = new Sort(
                [
                    'attributes'   => [
                        'id',
                        'name' => [
                            'asc'  => ['name' => SORT_ASC],
                            'desc' => ['name' => SORT_DESC],
                        ],
                        'tree',
                        'lft',
                    ],
                    'defaultOrder' => [
                        'tree' => SORT_ASC,
                        'lft'  => SORT_ASC,
                    ],
                ],
            );
            
            
            $alias = 'bk';
            
            $query = Book::find()
                         ->alias($alias)
                         ->noRoots($alias)
                         ->thisSite()
                         ->active($alias)
                         ->orderBy($sort->orders)
            ;
            
            if ($authorId !== null) {
                $query->andWhere(
                    [
                        '=',
                        'author_id',
                        $authorId,
                    ],
                );
            }
            
            if ($siteId !== null) {
                $query->andWhere(
                    [
                        '=',
                        'site_id',
                        $siteId,
                    ],
                );
            }
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query'      => $query,
                    'pagination' => [
                        'pageSize' => 50,
                    ],
                ],
            );
            
            if (!($this->load($params) && $this->validate())) {
                return $dataProvider;
            }
            
            $book = Book::findOne($this->id);
            
            $childIds = [];
            if ($book) {
                $childIds   = ArrayHelper::getColumn(
                    $book->children()->all(),
                    'id',
                );
                $childIds[] = $book->id;
            }
            
            // grid filtering conditions
            $query->andFilterWhere(
                [
                    $alias . '.id'        => $childIds,
                    $alias . '.author_id' => $this->author_id,
                    $alias . '.depth'     => $this->depth,
                ],
            );
            
            $query->andFilterWhere(['like', 'name', $this->name]);
            
            return $dataProvider;
        }
        
    }
