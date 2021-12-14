<?php

namespace Fuel\Models;

Class Sales
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function LastSales()
    {
        $query = 'select dt, amount from purchase order by dt desc, id_purchase desc limit 10';

        return $this
                ->db
                ->getList($query);
    }

    public function getLastPrice()
    {
        $query = 'select price from purchase where id_type = 1 order by dt desc limit 1';

        $last_price = $this
                        ->db
                        ->getValue($query);

        return ($last_price) ? $last_price : '';
    }

    public function AddPurchase($purchase)
    {
        $this
            ->db
            ->beginTransaction();

        $flag_error = false;

        $query = 'insert into purchase (dt, amount) values (:dt, :amount)';

        $res = $this
                    ->db
                    ->insertData($query, ['dt' => $purchase['dt'], 'amount' => $purchase['amount']]);

        $flag_error = (1 > $res);

        if (!$flag_error) {
            $litre = $purchase['summa'] / $purchase['price'];
            $query = 'insert into trips (dt, litre, distance, price) values (:dt, :litre, :distance, :price)';

            $res = $this
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
                ->db
                ->rollBack();

        } else {
            $this
                ->db
                ->commit();

        }

        return !$flag_error;
    }
}

