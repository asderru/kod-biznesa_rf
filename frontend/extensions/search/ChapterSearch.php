<?php
    
    namespace frontend\extensions\search;
    
    use core\edit\entities\Library\Chapter;
    use core\edit\traits\SearchTrait;
    use core\read\readers\Library\ChapterReader;
    use core\tools\Constant;
    use Exception;
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * ChapterSearch represents the model behind the search form of
     * `core\edit\entities\Library\Chapter`.
     */
    class ChapterSearch extends Chapter
    {
        use SearchTrait;
        
        public                 $book;
        public null|int|string $pageSize = null;
        private const string ALIAS = self::MODEL_ALIAS;
        
        /**
         * {@inheritdoc}
         */
        public function rules(): array
        {
            return [
                [
                    [
                        'id', 'site_id', 'user_id', 'book_id', 'author_id',
                        'photo',
                        'status', 'created_at', 'updated_at', 'sort',
                        'content_id',
                    
                    ], 'integer',
                ],
                [
                    [
                        'name', 'slug', 'video', 'title', 'description', 'text',
                    ],
                    'safe',
                ],
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
         * @param int|null $bookId
         * @return ActiveDataProvider
         * @throws Exception
         */
        public function search(
            array    $params,
            int|null $bookId,
            ?array $selected = null,
        ): ActiveDataProvider
        {
            $query = self::find()
                         ->alias(self::ALIAS)
                         ->andWhere(['>=', self::FIELD_STATUS, Constant::STATUS_ACTIVE])
            ;
            
            if ($bookId !== null) {
                $query->andWhere([self::FIELD_BOOK => $bookId]);
            }
            
            $selectedFields = ChapterReader::getSelected($selected, self::MODEL_ALIAS) ?? self::ALIASED_DEFAULT_FIELDS;
            
            $query->select($selectedFields);
            $this->load($params);
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => self::getSortOrders(),
                ],
            );
            
            $this->load($params);
            
            if (!$this->validate()) {
                // Возвращаем dataProvider без применения дополнительных условий,
                // если валидация не прошла
                return $dataProvider;
            }
            
            $dataProvider->pagination->pageSize = $this->pageSize;
            
            // grid filtering conditions
            $query->andFilterWhere(
                [
                    self::FIELD_ID   => $this->id,
                    self::FIELD_SORT => $this->sort,
                ],
            );
            
            $query->andFilterWhere(['like', self::FIELD_NAME, $this->name]);
            
            return $dataProvider;
        }
        
    }
