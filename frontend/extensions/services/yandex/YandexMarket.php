<?php
    
    namespace shop\services\yandex;
    
    use core\edit\entities\Shop\DeliveryMethod;
    use core\read\readModels\Shop\DeliveryMethodReadRepository;
    use core\read\readModels\Shop\ProductReadRepository;
    use core\read\readModels\Shop\RazdelReadRepository;
    use Throwable;
    use XMLWriter;
    use yii\bootstrap5\Html;
    
    class YandexMarket
    {
        private ShopInfo                     $shop;
        private RazdelReadRepository         $razdels;
        private ProductReadRepository        $products;
        private DeliveryMethodReadRepository $deliveryMethods;
        
        public function __construct(
            ShopInfo                     $shop,
            RazdelReadRepository $razdels,
            ProductReadRepository        $products,
            DeliveryMethodReadRepository $deliveryMethods,
        )
        {
            $this->shop            = $shop;
            $this->razdels = $razdels;
            $this->products        = $products;
            $this->deliveryMethods = $deliveryMethods;
        }
        
        /**
         * @throws Throwable
         */
        public function generate(callable $productUrlGenerator): string
        {
            ob_start();
            
            $writer = new XMLWriter();
            $writer->openURI('php://output');
            
            $writer->startDocument('1.0', 'UTF-8');
            $writer->startDTD('yml_catalog SYSTEM "shops.dtd"');
            $writer->endDTD();
            
            $writer->startElement('yml_catalog');
            $writer->writeAttribute('date', date('Y-m-d H:i'));
            
            $writer->startElement('shop');
            $writer->writeElement('name', Html::encode($this->shop->name));
            $writer->writeElement('company', Html::encode($this->shop->company));
            $writer->writeElement('url', Html::encode($this->shop->url));
            
            $writer->startElement('currencies');
            
            $writer->startElement('currency');
            $writer->writeAttribute('id', 'RUR');
            $writer->writeAttribute('rate', 1);
            $writer->endElement();
            
            $writer->endElement();
            
            $writer->startElement('razdels');
            
            foreach ($this->razdels::findModels() as $razdel) {
                $writer->startElement('razdel');
                $writer->writeAttribute('id', $razdel->id);
                if (($parent = $razdel->parent) && !$parent->isRoot()) {
                    $writer->writeAttribute('parentId', $parent->id);
                }
                $writer->writeRaw(Html::encode($razdel->name));
                $writer->endElement();
            }
            
            $writer->endElement();
            
            $writer->startElement('offers');
            
            $deliveries = $this->deliveryMethods->getAll();
            
            foreach ($this->products->getAllIterator() as $product) {
                $writer->startElement('offer');
                
                $writer->writeAttribute('id', $product->id);
                $writer->writeAttribute('type', 'vendor.model');
                $writer->writeAttribute('available', $product->isAvailable() ? 'true' : 'false');
                
                $writer->writeElement('url', Html::encode($productUrlGenerator($product)));
                $writer->writeElement('price', $product->price_new);
                $writer->writeElement('currencyId', 'RUR');
                $writer->writeElement('razdelId', $product->razdel_id);
                
                $available = array_filter($deliveries, static function (DeliveryMethod $method) use ($product) {
                    return $method->isAvailableForWeight($product->weight);
                });
                
                if ($available) {
                    $writer->writeElement('delivery', 'true');
                    $writer->writeElement(
                        'local_delivery_cost', max(
                        array_map(static function (DeliveryMethod $method) {
                            return $method->cost;
                        }, $available),
                    ),
                    );
                }
                else {
                    $writer->writeElement('delivery', 'false');
                }
                
                $writer->writeElement('vendor', Html::encode($product->brand->name));
                $writer->writeElement('model', Html::encode($product->code));
                $writer->writeElement('description', Html::encode(strip_tags($product->description)));
                
                foreach ($product->values as $value) {
                    if (!empty($value->value)) {
                        $writer->startElement('param');
                        $writer->writeAttribute('name', $value->characteristic->name);
                        $writer->text($value->value);
                        $writer->endElement();
                    }
                }
                
                $writer->endElement();
            }
            
            $writer->endElement();
            
            $writer->fullEndElement();
            $writer->fullEndElement();
            
            $writer->endDocument();
            
            return ob_get_clean();
        }
    }
