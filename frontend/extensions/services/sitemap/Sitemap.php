<?php
    
    namespace frontend\extensions\services\sitemap;
    
    use XMLWriter;
    
    class Sitemap
    {
        /**
         * @param iterable|IndexItem[] $items
         * @return string|int
         */
        public function generateIndex(iterable $items): string|int
        {
            $writer = new XMLWriter();
            $writer->openMemory();
            $writer->startDocument('1.0', 'UTF-8');
            $writer->setIndent(true);
            $writer->startElement('sitemapindex');
            $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            
            foreach ($items as $item) {
                $writer->startElement('sitemap');
                $writer->writeElement('loc', $item->location);
                if ($item->lastModified !== null) {
                    $writer->writeElement('lastmod', date('c', $item->lastModified));
                }
                $writer->endElement();
            }
            
            $writer->endElement();
            $writer->endDocument();
            
            return $writer->flush();
        }
        
        /**
         * @param iterable|MapItem[] $items
         * @return string|int
         */
        public function generateMap(iterable $items): string|int
        {
            $writer = new XMLWriter();
            $writer->openMemory();
            $writer->startDocument('1.0', 'UTF-8');
            $writer->setIndent(true);
            $writer->startElement('urlset');
            $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            
            foreach ($items as $item) {
                $writer->startElement('url');
                
                $writer->writeElement('loc', $item->location);
                if ($item->lastModified !== null) {
                    $writer->writeElement('lastmod', date('c', $item->lastModified));
                }
                if ($item->changeFrequency !== null) {
                    $writer->writeElement('changefreq', $item->changeFrequency);
                }
                if ($item->priority !== null) {
                    $writer->writeElement('priority', number_format($item->priority, 1, '.', ','));
                }
                $writer->endElement();
            }
            
            $writer->endElement();
            $writer->endDocument();
            
            return $writer->flush();
        }
    }
