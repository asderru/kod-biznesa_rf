<?php
    
    namespace frontend\extensions\services;
    
    use core\edit\repositories\Shop\ProductRepository;
    use core\read\cart\Cart;
    use core\read\cart\CartItem;
    
    class CartService
    {
        private Cart              $cart;
        private ProductRepository $products;
        
        public function __construct(Cart $cart, ProductRepository $products)
        {
            $this->cart     = $cart;
            $this->products = $products;
        }
        
        public function getCart(): Cart
        {
            return $this->cart;
        }
        
        public function add(int $productId, int $modificationId, int $quantity): void
        {
            $product = $this->products::get($productId);
            $modId   = $modificationId ? $product?->getModification($modificationId)->id : null;
            $this->cart->add(new CartItem($product, $modId, $quantity));
        }
        
        public function set(int $id, int $quantity): void
        {
            $this->cart->set($id, $quantity);
        }
        
        public function remove(int $id): void
        {
            $this->cart->remove($id);
        }
        
        public function clear(): void
        {
            $this->cart->clear();
        }
    }
