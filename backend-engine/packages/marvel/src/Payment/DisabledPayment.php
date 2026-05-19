<?php

namespace Marvel\Payments;

use Symfony\Component\HttpKernel\Exception\HttpException;

class DisabledPayment implements PaymentInterface
{
    private function disabled()
    {
        throw new HttpException(403, 'Payment processing is disabled for this MVP.');
    }

    public function getIntent(array $data): array
    {
        $this->disabled();
    }

    public function verify(string $id): mixed
    {
        $this->disabled();
    }

    public function handleWebHooks(object $request): void
    {
        $this->disabled();
    }

    public function createCustomer(object $request): array
    {
        $this->disabled();
    }

    public function attachPaymentMethodToCustomer(string $retrieved_payment_method, object $request): object
    {
        $this->disabled();
    }

    public function detachPaymentMethodToCustomer(string $retrieved_payment_method): object
    {
        $this->disabled();
    }

    public function retrievePaymentIntent(string $payment_intent_id): object
    {
        $this->disabled();
    }

    public function confirmPaymentIntent(string $payment_intent_id, array $data): object
    {
        $this->disabled();
    }

    public function setIntent(array $data): array
    {
        $this->disabled();
    }

    public function retrievePaymentMethod(string $method_key): object
    {
        $this->disabled();
    }
}

