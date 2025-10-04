<?php
    
    namespace frontend\extensions\helpers;
    
    use yii\bootstrap5\Html;
    
    class NestedTreeHelper
    {
        public function getAsideTree(
            array    $array,
            int      $parentId,
            int|null $activeId,
            string   $classLink,
            int      $iteration,
            string   $path,
            array    $parents = [],
        ): string
        {
            
            foreach ($array as $model) {
                $parents[] = $model->parent->id;
            }
            
            $menu_html = '';
            
            $listClass = 'dropdown' . $iteration + 1;
            
            foreach ($array as $model) {
                $classActive = ($model->id === $activeId)
                    ? ' active'
                    :
                    null;
                $url         = [
                    $path, 'id' => $model->id,
                ];
                
                if ($model->parent->id === $parentId) {
                    
                    if (in_array($model->id, $parents, true)) {
                        
                        $menu_html .= '<li class="list-group-item ' .
                                      $listClass . '">
						              ';
                    
                    }
                    else {
                        
                        $menu_html .= '<li class="list-group-item">
										';
                    
                    }
                    $menu_html .= Html::a(
                        $model->name,
                        $url,
                        [
                            'class' => $classLink .
                                       $classActive,
                        ],
                    
                    );
                    if (in_array($model->id, $parents, true)) {
                        $collapseId = 'collapseList' . $model->id;
                        
                        $menu_html .= '
						<span class="arrow-box">
						<a class="link-arrow collapsed"
                               data-bs-toggle="collapse"
                               href="#' . $collapseId . '"
                               role="button"
                               aria-expanded="false"
                               aria-controls="#' . $collapseId . '">
	                       <i class="arrow-white"></i>
	                    </a>
	                    </span>
                    
                        <div class="collapse" id="' . $collapseId . '">
                        
                        <ul class="dropdown">';
                        
                        $menu_html .= $this->getAsideTree(
                            $array,
                            $model->id,
                            $activeId,
                            $classLink,
                            $iteration + 1,
                            $path,
                            $parents,
                        );
                        
                        $menu_html .= '</ul>
									</div>';
                    }
                    $menu_html .= '</li>
									';
                }
            }
            return $menu_html;
            
        }
    }
