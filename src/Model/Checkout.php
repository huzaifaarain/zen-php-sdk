<?php

namespace Zen\Model;

use Zen\Exception\ZenException;
use Zen\Model\Checkout\BillingAddress;
use Zen\Model\Checkout\Customer;
use Zen\Model\Checkout\Item;
use Zen\Model\Checkout\ShippingAddress;

class Checkout {

    private ?float $amount;
    private ?string $currency;
    private string $merchantTransactionId;
    private ?Customer $customer = null;
    private ?ShippingAddress $shippingAddress = null;
    private ?BillingAddress $billingAddress = null;
    private ?array $items = null;
    private ?string $urlRedirect = null;
    private ?string $urlSuccess = null;
    private ?string $urlFailure = null;
    private ?string $customIpnUrl = null;

    public function __construct(string $merchantTransactionId) {
        $this->merchantTransactionId = $merchantTransactionId;
    }

    public function setAmount(float $amount): self {
        $this->amount = $amount;
        return $this;
    }

    public function setCurrency(string $currency): self {
        $this->currency = $currency;
        return $this;
    }


    public function addItem(Item $item): void {
        $this->items[] = $item;
    }

    public function setCustomer(Customer $customer): self {
        $this->customer = $customer;
        return $this;
    }

    public function setShippingAddress(ShippingAddress $shippingAddress): self {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    public function setBillingAddress(BillingAddress $billingAddress): self {
        $this->billingAddress = $billingAddress;
        return $this;
    }


    public function toArray(): array{
        $result = [];
        $result['merchantTransactionId'] = $this->merchantTransactionId;
        $result['amount'] = $this->amount;
        $result['currency'] = $this->currency;
        $result['customer'] = $this->customer->toArray();
        foreach ($this->items as $item) {
            $result['items'][] = $item->toArray();
        }


        return $result;

    }

    /**
     * @return void
     * @throws ZenException
     */
    public function validate(): void {

        if (is_null($this->customer)) {
            throw new ZenException("Checkout customer is required.");
        }
        if (is_null($this->items)) {
            throw new ZenException("At least one item is required.");
        }

        if (!is_null($this->urlRedirect) && !filter_var($this->urlRedirect, FILTER_VALIDATE_URL)){
            throw new ZenException("Checkout redirect URL is not a valid URL.");
        }
        if (!is_null($this->urlSuccess) && !filter_var($this->urlSuccess, FILTER_VALIDATE_URL)){
            throw new ZenException("Checkout success URL is not a valid URL.");
        }
        if (!is_null($this->urlFailure) && !filter_var($this->urlFailure, FILTER_VALIDATE_URL)){
            throw new ZenException("Checkout failure URL is not a valid URL.");
        }
        if (!is_null($this->customIpnUrl) && !filter_var($this->customIpnUrl, FILTER_VALIDATE_URL)){
            throw new ZenException("Checkout custom ipn URL is not a valid URL.");
        }


    }

}