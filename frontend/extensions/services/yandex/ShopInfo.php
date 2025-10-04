<?php
    
    namespace shop\services\yandex;
    
    class ShopInfo
    {
        public string $name;
        public string $company;
        public string $url;
        
        public function __construct(string $name, string $company, string $url)
        {
            $this->name    = $name;
            $this->company = $company;
            $this->url     = $url;
        }
    }
