<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\PaymentMethod;

class PaymentMethodPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->can('viewAnyPaymentMethod');
    }

    public function view(Admin $user, PaymentMethod $paymentMethod): bool
    {
        return $user->can('viewPaymentMethod');
    }

    public function create(Admin $user): bool
    {
        return $user->can('createPaymentMethod');
    }

    public function update(Admin $user, PaymentMethod $paymentMethod): bool
    {
        return $user->can('updatePaymentMethod');
    }

    public function delete(Admin $user, PaymentMethod $paymentMethod): bool
    {
        return $user->can('deletePaymentMethod');
    }
}
