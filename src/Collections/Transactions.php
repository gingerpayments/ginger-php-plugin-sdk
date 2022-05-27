<?php

namespace GingerPluginSdk\Collections;

use GingerPluginSdk\Entities\Transaction;
use GingerPluginSdk\Interfaces\AbstractCollectionContainerInterface;
use JetBrains\PhpStorm\Pure;

final class Transactions extends AbstractCollection implements AbstractCollectionContainerInterface
{
    const ITEM_TYPE = Transaction::class;

    public function __construct(Transaction ...$items)
    {
        $this->propertyName = 'transactions';
        foreach ($items as $item) {
            $this->add($item);
        }
        parent::__construct($item, 'transactions');
    }

    public function addTransaction(Transaction $transaction): Transactions
    {
        $this->add($transaction);
        return $this;
    }

    public function removeTransaction(string $index): Transactions
    {
        $this->remove($index);
        return $this;
    }
}