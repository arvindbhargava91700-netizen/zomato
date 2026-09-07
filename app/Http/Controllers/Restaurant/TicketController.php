<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\TicketController as BaseTicketController;
use App\Models\Restaurant;

class TicketController extends BaseTicketController
{
    protected string $guard = 'web';
    protected string $userType = 'restaurant';
    protected string $viewPrefix = 'manage.restaurant.tickets';
    protected string $routePrefix = 'restaurant.tickets.';

    protected function restaurantId(): ?int
    {
        $restaurant = Restaurant::where('user_id', $this->user()->id)->first();
        return $restaurant?->id;
    }
}
