<?php
    
    namespace frontend\extensions\search;
    
    use core\edit\entities\Forum\Thread;
    use core\edit\traits\SearchTrait;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use yii\data\Sort;
    
    /**
     * ThreadSearch represents the model behind the search form of
     * `core\edit\entities\Library\Thread`.
     */
    class ThreadSearch extends Thread
    {
        use SearchTrait;
        
        public $group;
        
        /**
         * {@inheritdoc}
         */
        public function rules(): array
        {
            return [
                [
                    [
                        'id', 'site_id', 'user_id', 'group_id', 'person_id',
                        'photo',
                        'status', 'created_at', 'updated_at', 'sort',
                        'content_id',
                    
                    ], 'integer',
                ],
                [
                    [
                        'name', 'slug', 'title', 'description', 'text',
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
         * @param int|null $groupId
         * @param int|null $comments
         * @return ActiveDataProvider
         */
        public function search(
            array    $params,
            int|null $groupId,
            int|null $comments,
        ): ActiveDataProvider
        {
            $sort = new Sort(
                [
                    'attributes'   => [
                        'group_id'   => [
                            'asc'     => ['group_id' => SORT_ASC],
                            'desc'    => ['group_id' => SORT_DESC],
                            'default' => SORT_ASC,
                        ],
                        'person_id'  => [
                            'asc'     => ['person_id' => SORT_ASC],
                            'desc'    => ['person_id' => SORT_DESC],
                            'default' => SORT_ASC,
                        ],
                        'name'       => [
                            'asc'     => ['name' => SORT_ASC],
                            'desc'    => ['name' => SORT_DESC],
                            'default' => SORT_ASC,
                        ],
                        'sort'       => [
                            'asc'     => ['sort' => SORT_ASC],
                            'desc'    => ['sort' => SORT_DESC],
                            'default' => SORT_ASC,
                        ],
                        'updated_at' => [
                            'asc'     => ['updated_at' => SORT_ASC],
                            'desc'    => ['updated_at' => SORT_DESC],
                            'default' => SORT_ASC,
                        ],
                    ],
                    'defaultOrder' => [
                        'sort' => SORT_ASC,
                    ],
                ],
            );
            
            $alias = 'th';
            
            $query = Thread::find()
                ->alias($alias)
                ->andWhere(
                    [
                        '>',
                        $alias . '.status',
                        Constant::STATUS_DRAFT,
                    ],
                )
                ->noRoots($alias)
                ->orderBy($sort->orders)
            ;
            
            if ($groupId !== null) {
                $query->andWhere(
                    [
                        '=',
                        'group_id',
                        $groupId,
                    ],
                );
            }
            
            if ($comments !== null) {
                $query->andWhere(
                    [
                        '>',
                        'comments_count',
                        0,
                    ],
                );
            }
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query'      => $query,
                    'pagination' => [
                        'pageSize' => 50,
                    ],
                ],
            );
            
            $this->load($params);
            
            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }
            
            // grid filtering conditions
            $query->andFilterWhere(
                [
                    'id'                => $this->id,
                    'group_id'          => $this->group_id,
                    'person_id'         => $this->person_id,
                    $alias . '.sort'    => $this->sort,
                    $alias . '.site_id' => $this->site_id,
                ],
            );
            
            $query->andFilterWhere(
                [
                    'like', $alias . '.name', $this->name,
                ],
            );
            
            return $dataProvider;
        }
        
    }
