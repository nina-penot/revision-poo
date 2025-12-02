<?php

interface StockableInterface
{
    function addStock(int $stock);

    function removeStock(int $stock);
}
