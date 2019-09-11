<?php
namespace Kna\Payum\YandexCheckout\Request;


use Kna\Payum\YandexCheckout\Request\Api\IdempotenceKeyAwareInterface;
use Kna\Payum\YandexCheckout\Request\Api\IdempotenceKeyAwareTrait;
use Payum\Core\Request\Capture as BaseCapture;
use YandexCheckout\Request\Payments\Payment\CreateCaptureRequest;

class Capture extends BaseCapture implements IdempotenceKeyAwareInterface
{
    use IdempotenceKeyAwareTrait;

    /**
     * @var CreateCaptureRequest|null
     */
    protected $createCaptureRequest;

    public function __construct($model, ?CreateCaptureRequest $createCaptureRequest = null, ?string $idempotenceKey = null)
    {
        parent::__construct($model);
        $this->createCaptureRequest = $createCaptureRequest;
        $this->idempotenceKey = $idempotenceKey;
    }

    /**
     * @return CreateCaptureRequest|null
     */
    public function getCreateCaptureRequest(): ?CreateCaptureRequest
    {
        return $this->createCaptureRequest;
    }

    /**
     * @param CreateCaptureRequest|null $createCaptureRequest
     */
    public function setCreateCaptureRequest(?CreateCaptureRequest $createCaptureRequest): void
    {
        $this->createCaptureRequest = $createCaptureRequest;
    }
}