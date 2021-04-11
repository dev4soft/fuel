<?php

namespace Fuel\Models;

Class Sales
{
    private $container;

    public function __construct($c)
    {
        $this->container = $c;
    }

    public function LastSales()
    {
        $query = 'select dt, amount from purchase order by dt desc, id_purchase desc limit 10';

        return $this
                ->container
                ->db
                ->getList($query);
    }

    public function getLastPrice()
    {
        $query = 'select price from purchase where id_type = 1 order by dt desc limit 1';

        $last_price = $this
                        ->container
                        ->db
                        ->getValue($query);

        return ($last_price) ? $last_price : '';
    }

    public function AddPurchase($purchase)
    {
        $this
            ->container
            ->db
            ->beginTransaction();

        $flag_error = false;

        $query = 'insert into purchase (dt, amount) values (:dt, :amount)';

        $res = $this
                    ->container
                    ->db
                    ->insertData($query, ['dt' => $purchase['dt'], 'amount' => $purchase['amount']]);

        $flag_error = (1 > $res);

        if (!$flag_error) {
            $litre = $purchase['summa'] / $purchase['price'];
            $query = 'insert into trips (dt, litre, distance, price) values (:dt, :litre, :distance, :price)';

            $res = $this
                        ->container
                        ->db
                        ->insertData($query,
                            [
                                'dt' => $purchase['dt'],
                                'litre' => $purchase['litre'],
                                'distance' => $purchase['distance'],
                                'price' => $purchase['price'],
                            ]);

            $flag_error = (1 > $res);
        }

        if ($flag_error) {
            $this
                ->container
                ->db
                ->rollBack();

        } else {
            $this
                ->container
                ->db
                ->commit();

        }

        return !$flag_error;
    }
}

