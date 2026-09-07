<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\TicketController as BaseTicketController;

class TicketController extends BaseTicketController
{
    protected string $guard = 'web';
    protected string $userType = 'delivery_partner';
    protected string $viewPrefix = 'manage.delivery-partner.tickets';
    protected string $routePrefix = 'delivery-partner.tickets.';

    protected function restaurantId(): ?int
    {
        return null;
    }
}
