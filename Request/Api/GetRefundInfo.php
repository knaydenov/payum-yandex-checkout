<?php


namespace Kna\Payum\YandexCheckout\Request\Api;


class GetRefundInfo
{
    use RefundAwareTrait;
    /**
     * @var string
     */
    protected $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}