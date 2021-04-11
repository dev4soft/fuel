<?php

namespace Fuel\Controllers;

Class Form
{
    private $container;

    public function __construct($c)
    {
        $this->container = $c;
    }

    public function OutMenu($request, $response)
    {
        return $this
                ->container
                ->view
                ->render($response, 'menu.php');
    }

    public function OutForm($request, $response)
    {
        $price = (new \Fuel\Models\Sales($this->container))->getLastPrice();
        $dt = date('Y-m-d');

        return $this
                ->container
                ->view
                ->render($response, 'add.php', [
                    'price' => $price,
                    'dt'    => $dt,
                ]);
    }

    public function SavePurchase($request, $response)
    {
        $data = $request->getParsedBody();

        $purchase = [
            'dt'       => $data['dt'],
            'distance' => $data['distance'],
            'summa'    => $data['summa'],
            'price'    => $data['price'],
        ];

        $sales = new \Fuel\Models\Sales($this->container);

        $sales->AddPurchase($purchase);

        return $this->OutForm($request, $response);
    }
}

