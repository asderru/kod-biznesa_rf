<?php
    
    namespace frontend\extensions\services;
    
    
    use core\edit\entities\Shop\Order\Order;
    use core\edit\entities\Shop\Order\OrderItem;
    use core\edit\repositories\Shop\DeliveryMethodRepository;
    use core\edit\repositories\Shop\OrderRepository;
    use core\edit\repositories\Shop\ProductRepository;
    use core\edit\repositories\User\UserRepository;
    use core\read\cart\Cart;
    use core\read\cart\CartItem;
    use Exception;
    use frontend\extensions\forms\OrderForm;
    
    class OrderService
    {
        private Cart                     $cart;
        private OrderRepository          $orders;
        private ProductRepository        $products;
        private UserRepository           $users;
        private DeliveryMethodRepository $deliveryMethods;
        private TransactionManager       $transaction;
        
        public function __construct(
            Cart                     $cart,
            OrderRepository          $orders,
            ProductRepository        $products,
            UserRepository           $users,
            DeliveryMethodRepository $deliveryMethods,
            TransactionManager       $transaction,
        )
        {
            $this->cart            = $cart;
            $this->orders          = $orders;
            $this->products        = $products;
            $this->users           = $users;
            $this->deliveryMethods = $deliveryMethods;
            $this->transaction     = $transaction;
        }
        
        /**
         * @throws Exception
         */
        public function checkout(int $userId, OrderForm $form): Order
        {
            $user = $this->users::get($userId);
            
            $products = [];
            
            $items = array_map(static function (CartItem $item) use (&$products) {
                $product = $item->getProduct();
                $product->checkout($item->getModificationId(), $item->getQuantity());
                $products[] = $product;
                return OrderItem::create(
                    $product,
                    $item->getModificationId(),
                    $item->getPrice(),
                    $item->getQuantity(),
                );
            }, $this->cart->getItems());
            
            $order = Order::create(
                $user->id,
                new CustomerData(
                    $form->customer->phone,
                    $form->customer->name,
                ),
                $items,
                $this->cart->getCost()->getTotal(),
                $form->note,
            );
            
            $order->setDeliveryInfo(
                $this->deliveryMethods->get($form->delivery->method),
                new DeliveryData(
                    $form->delivery->index,
                    $form->delivery->address,
                ),
            );
            
            $this->transaction->wrap(function () use ($order, $products) {
                $this->orders::save($order);
                foreach ($products as $product) {
                    $this->products::save($product);
                }
                $this->cart->clear();
            });
            
            return $order;
        }
    }
