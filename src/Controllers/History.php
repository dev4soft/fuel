<?php

namespace Fuel\Controllers;

Class History
{
    private $container;

    public function __construct($c)
    {
        $this->container = $c;
    }

    public function OutSales($request, $response)
    {

        $sales = new \Fuel\Models\Sales($this->container);

        $data = $sales->LastSales();

        return $this
                ->container
                ->view
                ->render($response, 'history.php', [
                    'data' => $data,
                ]);
    }
}

