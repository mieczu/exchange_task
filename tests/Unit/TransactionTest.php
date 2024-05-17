<?php

use App\ExchangeTransaction\Domain\Service\ExchangeService;
use App\ExchangeTransaction\Domain\Transaction;
use App\ExchangeTransaction\Domain\TransactionId;
use App\ExchangeTransaction\Domain\TransactionType;
use App\ExchangeTransaction\Infrastructure\ExchangeRate\InMemoryExchangeRateRepository;
use App\SharedKernel\Domain\Currency;
use App\SharedKernel\Domain\IDateTimeProvider;
use App\SharedKernel\Domain\Money;
use App\SharedKernel\Infrastructure\Service\SystemDateTimeProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Transaction::class)]
class TransactionTest extends TestCase
{
    private IDateTimeProvider $dateTimeProvider;
    private ExchangeService $exchangeService;

    protected function setUp(): void
    {
        $repository = new InMemoryExchangeRateRepository();
        $this->exchangeService = new ExchangeService($repository->getAll());
        $this->dateTimeProvider = $this->createMock(SystemDateTimeProvider::class);
    }

    public function testSellEurosForPounds(): void
    {
        $moneyToSell = new Money(100, new Currency('EUR'));
        $toCurrency = new Currency('GBP');
        $transaction = new Transaction(
            new TransactionId('test1234'),
            $this->dateTimeProvider
        );
        $transaction->sell($moneyToSell, $toCurrency, $this->exchangeService, $this->dateTimeProvider);

        $this->assertCount(2, $transaction->getEventsToPublish());
        $event = $transaction->getEventsToPublish()[1];
        $this->assertEquals('155.2122', $event->toAmount);
        $this->assertEquals('GBP', $event->toCurrency);
        $this->assertEquals(TransactionType::SELL->name, $event->transactionType);
    }

    public function testBuyEurosForPounds(): void
    {
        $moneyToBuy = new Money(100, new Currency('EUR'));
        $fromCurrency = new Currency('GBP');
        $transaction = new Transaction(
            new TransactionId('test1234'),
            $this->dateTimeProvider
        );
        $transaction->buy($moneyToBuy, $fromCurrency, $this->exchangeService, $this->dateTimeProvider);

        $this->assertCount(2, $transaction->getEventsToPublish());
        $event = $transaction->getEventsToPublish()[1];

        $this->assertEquals('152.7768', $event->fromAmount);
        $this->assertEquals('EUR', $event->toCurrency);
        $this->assertEquals(TransactionType::BUY->name, $event->transactionType);
    }

    public function testSellPoundsForEuros(): void
    {
        $moneyToSell = new Money(100, new Currency('GBP'));
        $toCurrency = new Currency('EUR');
        $transaction = new Transaction(
            new TransactionId('test1234'),
            $this->dateTimeProvider
        );
        $transaction->sell($moneyToSell, $toCurrency, $this->exchangeService, $this->dateTimeProvider);

        $this->assertCount(2, $transaction->getEventsToPublish());
        $event = $transaction->getEventsToPublish()[1];
        $this->assertEquals('152.7768', $event->toAmount);
        $this->assertEquals('EUR', $event->toCurrency);
        $this->assertEquals(TransactionType::SELL->name, $event->transactionType);
    }

    public function testBuyPoundsForEuros(): void
    {
        $moneyToBuy = new Money(100, new Currency('GBP'));
        $fromCurrency = new Currency('EUR');
        $transaction = new Transaction(
            new TransactionId('test1234'),
            $this->dateTimeProvider
        );
        $transaction->buy($moneyToBuy, $fromCurrency, $this->exchangeService, $this->dateTimeProvider);

        $this->assertCount(2, $transaction->getEventsToPublish());
        $event = $transaction->getEventsToPublish()[1];

        $this->assertEquals('155.2122', $event->fromAmount);
        $this->assertEquals('GBP', $event->toCurrency);
        $this->assertEquals(TransactionType::BUY->name, $event->transactionType);
    }
}