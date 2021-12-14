<?php

namespace Fuel\Controllers;

Class History
{
    private $sales;
    private $view;

    public function __construct($container)
    {
        $this->sales = $container['sales'];
        $this->view = $container['view'];
    }

    public function OutSales($request, $response)
    {
        $data = $this->sales->LastSales();

        return $this
                ->view
                ->render($response, 'history.php', [
                    'data' => $data,
                ]);
    }
}

