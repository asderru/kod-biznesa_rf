<?php
    
    namespace frontend\extensions\services\sitemap;
    
    class MapItem
    {
        public const ALWAYS  = 'always';
        public const HOURLY  = 'hourly';
        public const DAILY   = 'daily';
        public const WEEKLY  = 'weekly';
        public const MONTHLY = 'monthly';
        public const YEARLY  = 'yearly';
        public const NEVER   = 'never';
        
        public string  $location;
        public ?string $lastModified;
        public ?string $changeFrequency;
        public ?string $priority;
        
        public function __construct(
            string      $location,
            string|null $lastModified = null,
            string|null $changeFrequency = null,
            string|null $priority = null,
        )
        {
            $this->location        = $location;
            $this->lastModified    = $lastModified;
            $this->changeFrequency = $changeFrequency;
            $this->priority        = $priority;
        }
    }
