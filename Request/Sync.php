<?php
namespace Kna\Payum\YandexCheckout\Request;


use Payum\Core\Request\Sync as BaseSync;
use YandexCheckout\Model\Payment;

class Sync extends BaseSync
{
    /**
     * @var Payment|Refund|null
     */
    protected $replace;

    public function __construct($model, $replace = null)
    {
        parent::__construct($model);
        $this->replace = $replace;
    }

    /**
     * @return Refund|Payment|null
     */
    public function getReplace()
    {
        return $this->replace;
    }

    /**
     * @param Refund|Payment|null $replace
     */
    public function setReplace($replace): void
    {
        $this->replace = $replace;
    }

}