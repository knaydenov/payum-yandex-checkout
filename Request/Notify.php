<?php
namespace Kna\Payum\YandexCheckout\Request;


use Payum\Core\Request\Sync as BaseSync;
use YandexCheckout\Model\Notification\AbstractNotification;

class Notify extends BaseSync
{
    /**
     * @var AbstractNotification|null
     */
    protected $notification;

    public function __construct($model, ?AbstractNotification $notification = null)
    {
        parent::__construct($model);
        $this->notification = $notification;

        if ($model instanceof AbstractNotification) {
            $this->notification = $model;
        }
    }

    /**
     * @return AbstractNotification|null
     */
    public function getNotification(): ?AbstractNotification
    {
        return $this->notification;
    }

    /**
     * @param AbstractNotification|null $notification
     */
    public function setNotification(?AbstractNotification $notification): void
    {
        $this->notification = $notification;
    }
}