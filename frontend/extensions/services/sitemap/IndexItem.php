<?php
    
    namespace frontend\extensions\services\sitemap;
    
    class IndexItem
    {
        public string  $location;
        public ?string $lastModified;
        
        public function __construct(string $location, string|null $lastModified = null)
        {
            $this->location     = $location;
            $this->lastModified = $lastModified;
        }
    }
