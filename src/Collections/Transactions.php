<?php

namespace GingerPluginSdk\Collections;

use GingerPluginSdk\Entities\Transaction;
use GingerPluginSdk\Interfaces\AbstractCollectionContainerInterface;

final class Transactions extends AbstractCollection implements AbstractCollectionContainerInterface
{
    const ITEM_TYPE = Transaction::class;

    public function __construct(Transaction ...$items)
    {
        $this->propertyName = 'transactions';
        foreach ($items as $item) {
            $this->add($item);
        }
        parent::__construct('transactions');
    }

    /**
     * @param \GingerPluginSdk\Entities\Transaction $transaction
     * @return $this
     */
    public function addTransaction(Transaction $transaction): Transactions
    {
        $this->add($transaction);
        return $this;
    }

    /**
     * @param string $index
     * @return $this
     */
    public function removeTransaction(string $index): Transactions
    {
        $this->remove($index);
        return $this;
    }

    public function updateTransaction(Transaction $transaction, $index = null): static
    {
        $this->update($transaction->toArray(), $index);
        return $this;
    }
}