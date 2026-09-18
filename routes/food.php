<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DeliveryPartner\DashboardController as DeliveryDashboardController;
use App\Http\Controllers\frontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Restaurant\TicketController as RestaurantTicketController;
use App\Http\Controllers\DeliveryPartner\TicketController as DeliveryPartnerTicketController;
use App\Http\Controllers\Restaurant\DashboardController as RestaurantDashboardController;
use Illuminate\Support\Facades\Route;

// Restaurant Owner Panel
 

Route::get('/restaurants', [App\Http\Controllers\PublicRestaurantController::class, 'index'])->name('public.restaurants.index');
Route::get('/restaurants/nearby', [App\Http\Controllers\PublicRestaurantController::class, 'nearby'])->name('public.restaurants.nearby');
Route::get('/restaurants/location-suggest', [App\Http\Controllers\PublicRestaurantController::class, 'locationSuggest'])->name('public.restaurants.location-suggest');
Route::get('/restaurants/search-location', [App\Http\Controllers\PublicRestaurantController::class, 'searchLocation'])->name('public.restaurants.search-location');
Route::get('/restaurants/nightlife', [App\Http\Controllers\PublicRestaurantController::class, 'nightlife'])->name('public.restaurants.nightlife');
Route::get('/restaurants/{restaurant:restaurant_slug}', [App\Http\Controllers\PublicRestaurantController::class, 'show'])->name('public.restaurants.show');
Route::get('/dining-offers/by-location', [frontController::class, 'diningOffersByLocation'])->name('public.dining-offers.location');
Route::get('/dining-out', [frontController::class, 'diningOutListing'])->name('dining.out');
Route::get('/dining-slots/{restaurant}', [frontController::class, 'getDiningSlots'])->name('public.dining.slots');

// Collections Routes
Route::get('/collections', [frontController::class, 'collectionsList'])->name('collections.index');
Route::get('/collection', [frontController::class, 'collectionDetails'])->name('collections.show');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Become a Vendor (Restaurant Partner) Registration
Route::get('/become-a-vendor', [App\Http\Controllers\VendorRegisterController::class, 'showRegistrationForm'])->name('vendor.register');
Route::post('/become-a-vendor', [App\Http\Controllers\VendorRegisterController::class, 'register'])->name('vendor.register.submit');

// Become a Courier (Delivery Partner) Registration
Route::get('/become-a-courier', [App\Http\Controllers\DeliveryPartnerRegisterController::class, 'showRegistrationForm'])->name('delivery-partner.register');
Route::post('/become-a-courier', [App\Http\Controllers\DeliveryPartnerRegisterController::class, 'register'])->name('delivery-partner.register.submit');

//website
Route::get('/', [frontController::class,'index'])->name('index');
Route::get('/menu-listing', [frontController::class, 'menuListing'])->name('menu.list');
Route::get('/category-foods', [frontController::class, 'categoryFoods'])->name('category.foods');
Route::post('/book-table', [frontController::class, 'bookTableStore'])->name('booking.store');
Route::post('/razorpay/create-order', [frontController::class, 'createRazorpayOrder'])->name('razorpay.create-order');
Route::get('/blog-listing', [frontController::class, 'blogListing'])->name('blog.list');
Route::get('/blog', [frontController::class, 'blogDetails'])->name('blog.show');
Route::get('/blog-details', [frontController::class, 'blogDetails'])->name('blog.details');
Route::get('/blog/{slug}', [frontController::class, 'blogDetails']);

// Blog Comments & Reactions
Route::post('/blog/{blog}/comments', [App\Http\Controllers\BlogCommentController::class, 'store'])->name('blog.comments.store');
Route::put('/blog/comments/{comment}', [App\Http\Controllers\BlogCommentController::class, 'update'])->name('blog.comments.update');
Route::delete('/blog/comments/{comment}', [App\Http\Controllers\BlogCommentController::class, 'destroy'])->name('blog.comments.destroy');
Route::post('/blog/{blog}/react', [App\Http\Controllers\BlogCommentController::class, 'reactBlog'])->name('blog.react');
Route::post('/blog/comments/{comment}/react', [App\Http\Controllers\BlogCommentController::class, 'reactComment'])->name('blog.comments.react');



Route::get('/faq-list', [frontController::class, 'faqList'])->name('faq.list');
Route::get('/testomonial-list', [frontController::class, 'testomonialList'])->name('testomonial.list');
Route::get('/contact', [frontController::class, 'contact'])->name('contact');
Route::post('/contact', [frontController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/checkout', [frontController::class, 'checkout'])->name('checkout');

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/wish-list', [frontController::class, 'wishList'])->name('wish.list');
    Route::delete('/wish-list/{id}', [frontController::class, 'wishlistDestroy'])->name('wishlist.remove');
    Route::post('/wishlist/toggle', [frontController::class, 'wishlistStore'])->name('wishlist.store');
    
    Route::get('/address', [frontController::class, 'address'])->name('address');
    Route::get('/address-api/{id}', [frontController::class, 'addressApi']);
    Route::get('/restaurant-api/{id}', [frontController::class, 'restaurantApi']);
    Route::get('/payment', [frontController::class, 'payment'])->name('payment');
    Route::get('/confirmOrder', [frontController::class, 'confirmOrder'])->name('confirmOrder');
    Route::post('/place-order', [frontController::class, 'placeOrder'])->name('place.order');
    Route::post('/promo/validate', [frontController::class, 'validatePromo'])->name('promo.validate');
    Route::get('/orderTracking', [frontController::class, 'orderTracking'])->name('orderTracking');
    Route::post('/orders/{order}/cancel', [frontController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/review', [frontController::class, 'storeReview'])->name('review.store');
    Route::put('/review/{review}', [frontController::class, 'updateReview'])->name('review.update');
    Route::delete('/review/{review}', [frontController::class, 'destroyReview'])->name('review.destroy');

    Route::get('/profile', [frontController::class, 'profile'])->name('profile');
    Route::get('/wallet', [frontController::class, 'wallet'])->name('wallet');
    Route::post('/wallet/topup', [frontController::class, 'walletTopup'])->name('wallet.topup');
    Route::post('/wallet/topup/success', [frontController::class, 'walletTopupSuccess'])->name('wallet.topup.success');
    Route::get('/my-orders', [frontController::class, 'myOrders'])->name('my.orders');
    Route::get('/my-orders/{order}/invoice', [frontController::class, 'downloadOrderInvoice'])->name('my.orders.invoice');
    Route::get('/my-transactions', [frontController::class, 'myTransactions'])->name('my.transactions');
    Route::get('/my-transactions/{transaction}/receipt', [frontController::class, 'downloadReceipt'])->name('my.transactions.receipt');
    Route::get('/my-feedback', [frontController::class, 'myFeedback'])->name('my.feedback');
    Route::get('/saved-address', [frontController::class, 'savedAddress'])->name('saved.address');
    Route::post('/saved-address', [frontController::class, 'addressStore'])->name('address.store');
    Route::put('/saved-address/{id}', [frontController::class, 'addressUpdate'])->name('address.update');
    Route::delete('/saved-address/{id}', [frontController::class, 'addressDestroy'])->name('address.destroy');
    Route::get('/saved-card', [frontController::class, 'savedCard'])->name('saved.card');
    Route::post('/saved-card', [frontController::class, 'cardStore'])->name('card.store');
    Route::put('/saved-card/{id}', [frontController::class, 'cardUpdate'])->name('card.update');
    Route::delete('/saved-card/{id}', [frontController::class, 'cardDestroy'])->name('card.destroy');
    Route::get('/setting', [frontController::class, 'setting'])->name('setting');

    Route::post('/profile/update-name', [frontController::class, 'profileUpdateName'])->name('profile.update-name');
    Route::post('/profile/update-email', [frontController::class, 'profileUpdateEmail'])->name('profile.update-email');
    Route::post('/profile/update-phone', [frontController::class, 'profileUpdatePhone'])->name('profile.update-phone');
    Route::post('/profile/update-password', [frontController::class, 'profileUpdatePassword'])->name('profile.update-password');
    Route::post('/profile/update-image', [frontController::class, 'profileUpdateImage'])->name('profile.update-image');
    Route::post('/profile/delete-account', [frontController::class, 'deleteAccount'])->name('profile.delete-account');
    Route::post('/profile/notification-update', [frontController::class, 'notificationUpdate'])->name('profile.notification-update');

    // Support Tickets (Customer)
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets-index');
    Route::get('tickets-create', [TicketController::class, 'create'])->name('tickets-create');
    Route::post('tickets', [TicketController::class, 'store'])->name('tickets-store');
    Route::get('tickets-{ticket}', [TicketController::class, 'show'])->name('tickets-show');
    Route::post('tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets-reply');
    Route::post('tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets-close');
    Route::post('tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets-reopen');
    Route::post('tickets/{ticket}/rate', [TicketController::class, 'rate'])->name('tickets-rate');
    Route::get('help', [TicketController::class, 'help'])->name('help');
});

// Restaurant Owner Auth (guest)
Route::get('/restaurant/login', [App\Http\Controllers\Restaurant\Auth\LoginController::class, 'showLoginForm'])->name('restaurant.login');
Route::post('/restaurant/login', [App\Http\Controllers\Restaurant\Auth\LoginController::class, 'login'])->name('restaurant.login.submit');
Route::get('/restaurant/forgot-password', [App\Http\Controllers\Restaurant\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('restaurant.forgot_password');

// Delivery Partner Auth (guest)
Route::get('/delivery-partner/login', [App\Http\Controllers\DeliveryPartner\Auth\LoginController::class, 'showLoginForm'])->name('delivery-partner.login');
Route::post('/delivery-partner/login', [App\Http\Controllers\DeliveryPartner\Auth\LoginController::class, 'login'])->name('delivery-partner.login.submit');

/*
|--------------------------------------------------------------------------
| Role Based Panel Routes
|--------------------------------------------------------------------------
| Routes below are grouped by the authenticated user's role (users.role_id).
|
*/

// Restaurant Owner Panel
Route::middleware('role:restaurant_owner')->prefix('restaurant')->name('restaurant.')->group(function () {
    Route::get('/dashboard', [RestaurantDashboardController::class, 'index'])->name('dashboard');

    Route::get('/earnings', [App\Http\Controllers\Restaurant\EarningsController::class, 'index'])->name('earnings.index');
  Route::get('/withdrawals', [App\Http\Controllers\Restaurant\WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [App\Http\Controllers\Restaurant\WithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [App\Http\Controllers\Restaurant\WithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/orders', [App\Http\Controllers\Restaurant\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Restaurant\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/accept', [App\Http\Controllers\Restaurant\OrderController::class, 'accept'])->name('orders.accept');
    Route::post('/orders/{order}/reject', [App\Http\Controllers\Restaurant\OrderController::class, 'reject'])->name('orders.reject');
    Route::post('/orders/{order}/preparing', [App\Http\Controllers\Restaurant\OrderController::class, 'preparing'])->name('orders.preparing');
    Route::post('/orders/{order}/ready', [App\Http\Controllers\Restaurant\OrderController::class, 'ready'])->name('orders.ready');
    Route::post('/orders/{order}/assign-partner', [App\Http\Controllers\Restaurant\OrderController::class, 'assignPartner'])->name('orders.assign-partner');
    Route::post('/orders/{order}/send-request', [App\Http\Controllers\Restaurant\OrderController::class, 'sendRequest'])->name('orders.send-request');

    Route::get('/profile', [App\Http\Controllers\Restaurant\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\Restaurant\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [App\Http\Controllers\Restaurant\ProfileController::class, 'changePassword'])->name('password.update');
    Route::get('/account-settings', [App\Http\Controllers\Restaurant\SettingController::class, 'accountSettings'])->name('account.settings');
    Route::post('/account-settings', [App\Http\Controllers\Restaurant\SettingController::class, 'accountSettingsUpdate'])->name('account.settings.update');

    // Notifications (Restaurant Owner)
    Route::get('/notifications', [App\Http\Controllers\Restaurant\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [App\Http\Controllers\Restaurant\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\Restaurant\NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('/notifications/read-all', [App\Http\Controllers\Restaurant\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    Route::get('/restaurants', [App\Http\Controllers\Restaurant\RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('/restaurants/create', [App\Http\Controllers\Restaurant\RestaurantController::class, 'create'])->name('restaurants.create');
    Route::post('/restaurants', [App\Http\Controllers\Restaurant\RestaurantController::class, 'store'])->name('restaurants.store');
    Route::get('/restaurants/{restaurant}', [App\Http\Controllers\Restaurant\RestaurantController::class, 'show'])->name('restaurants.show');
    Route::get('/restaurants/{restaurant}/edit', [App\Http\Controllers\Restaurant\RestaurantController::class, 'edit'])->name('restaurants.edit');
    Route::put('/restaurants/{restaurant}', [App\Http\Controllers\Restaurant\RestaurantController::class, 'update'])->name('restaurants.update');
    Route::post('/restaurants/{restaurant}/resubmit', [App\Http\Controllers\Restaurant\RestaurantController::class, 'resubmit'])->name('restaurants.resubmit');

    Route::get('/cuisines', [App\Http\Controllers\Restaurant\CuisineController::class, 'index'])->name('cuisines.index');
    Route::post('/cuisines', [App\Http\Controllers\Restaurant\CuisineController::class, 'update'])->name('cuisines.update');

    Route::patch('/food-categories/{food_category}/toggle-status', [App\Http\Controllers\Restaurant\FoodCategoryController::class, 'toggleStatus'])->name('food-categories.toggle-status');
    Route::post('/food-categories/{id}/restore', [App\Http\Controllers\Restaurant\FoodCategoryController::class, 'restore'])->name('food-categories.restore');
    Route::delete('/food-categories/{id}/force-delete', [App\Http\Controllers\Restaurant\FoodCategoryController::class, 'forceDelete'])->name('food-categories.force-delete');
    Route::resource('food-categories', App\Http\Controllers\Restaurant\FoodCategoryController::class);

    Route::patch('/foods/{food}/toggle-status', [App\Http\Controllers\Restaurant\FoodItemController::class, 'toggleStatus'])->name('foods.toggle-status');
    Route::post('/foods/{id}/restore', [App\Http\Controllers\Restaurant\FoodItemController::class, 'restore'])->name('foods.restore');
    Route::delete('/foods/{id}/force-delete', [App\Http\Controllers\Restaurant\FoodItemController::class, 'forceDelete'])->name('foods.force-delete');
    Route::resource('foods', App\Http\Controllers\Restaurant\FoodItemController::class);

    Route::patch('/food-variants/{food_variant}/toggle-status', [App\Http\Controllers\Restaurant\FoodVariantController::class, 'toggleStatus'])->name('food-variants.toggle-status');
    Route::post('/food-variants/{id}/restore', [App\Http\Controllers\Restaurant\FoodVariantController::class, 'restore'])->name('food-variants.restore');
    Route::delete('/food-variants/{id}/force-delete', [App\Http\Controllers\Restaurant\FoodVariantController::class, 'forceDelete'])->name('food-variants.force-delete');
    Route::resource('food-variants', App\Http\Controllers\Restaurant\FoodVariantController::class);

    // Menus (Upload menu with images)
    Route::patch('/menus/{menu}/toggle-status', [App\Http\Controllers\Restaurant\MenuController::class, 'toggleStatus'])->name('menus.toggle-status');
    Route::post('/menus/{id}/restore', [App\Http\Controllers\Restaurant\MenuController::class, 'restore'])->name('menus.restore');
    Route::delete('/menus/{id}/force-delete', [App\Http\Controllers\Restaurant\MenuController::class, 'forceDelete'])->name('menus.force-delete');
    Route::resource('menus', App\Http\Controllers\Restaurant\MenuController::class);

    // Dining Offers (Book-a-table discounts)
    Route::patch('/dining-offers/{dining_offer}/toggle-status', [App\Http\Controllers\Restaurant\DiningOfferController::class, 'toggleStatus'])->name('dining-offers.toggle-status');
    Route::resource('dining-offers', App\Http\Controllers\Restaurant\DiningOfferController::class);

    // Dining Setup & Table Management
    Route::prefix('dining-setup')->name('dining-setup.')->group(function () {
        Route::get('/', [App\Http\Controllers\Restaurant\DiningSetupController::class, 'index'])->name('index');
        Route::post('/settings', [App\Http\Controllers\Restaurant\DiningSetupController::class, 'updateSettings'])->name('settings.update');

        // Dedicated Pages for Table Management (Pages, Not Popups)
        Route::get('/tables/create', [App\Http\Controllers\Restaurant\DiningSetupController::class, 'createTable'])->name('tables.create');
        Route::post('/tables', [App\Http\Controllers\Restaurant\DiningSetupController::class, 'storeTable'])->name('tables.store');
        Route::get('/tables/{table}/edit', [App\Http\Controllers\Restaurant\DiningSetupController::class, 'editTable'])->name('tables.edit');
        Route::put('/tables/{table}', [App\Http\Controllers\Restaurant\DiningSetupController::class, 'updateTable'])->name('tables.update');
        Route::delete('/tables/{table}', [App\Http\Controllers\Restaurant\DiningSetupController::class, 'destroyTable'])->name('tables.destroy');
        Route::patch('/tables/{table}/toggle-status', [App\Http\Controllers\Restaurant\DiningSetupController::class, 'toggleTableStatus'])->name('tables.toggle-status');

        // Dedicated Page for Quick Batch Table Generation
        Route::get('/tables/batch-create', [App\Http\Controllers\Restaurant\DiningSetupController::class, 'batchCreatePage'])->name('tables.batch-create');
        Route::post('/tables/batch-create', [App\Http\Controllers\Restaurant\DiningSetupController::class, 'batchCreateTables'])->name('tables.batch');

        Route::get('/slot-availability', [App\Http\Controllers\Restaurant\DiningSetupController::class, 'getSlotAvailabilityApi'])->name('slots.availability');
    });


    // Restaurant Offers (Today's Deal banners, admin-approved)
    Route::patch('/restaurant-offers/{restaurant_offer}/toggle-status', [App\Http\Controllers\Restaurant\RestaurantOfferController::class, 'toggleStatus'])->name('restaurant-offers.toggle-status');
    Route::resource('restaurant-offers', App\Http\Controllers\Restaurant\RestaurantOfferController::class);

    // Table Bookings
    Route::get('/bookings', [App\Http\Controllers\Restaurant\BookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}/status', [App\Http\Controllers\Restaurant\BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::get('/bookings/{booking}/bill', [App\Http\Controllers\Restaurant\BookingController::class, 'bill'])->name('bookings.bill.show');
    Route::post('/bookings/{booking}/bill', [App\Http\Controllers\Restaurant\BookingController::class, 'billStore'])->name('bookings.bill.store');
    Route::post('/bookings/{booking}/bill/mark-paid', [App\Http\Controllers\Restaurant\BookingController::class, 'markAsPaid'])->name('bookings.bill.mark-paid');
    Route::post('/bookings/{booking}/bill/delete', [App\Http\Controllers\Restaurant\BookingController::class, 'deleteBill'])->name('bookings.bill.delete');

    // Restaurant Blogs & Stories
    Route::patch('/blogs/{blog}/toggle-status', [App\Http\Controllers\Restaurant\BlogController::class, 'toggleStatus'])->name('blogs.toggle-status');
    Route::resource('blogs', App\Http\Controllers\Restaurant\BlogController::class);



    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Geo / Location helpers (reverse geocode + dependent dropdowns)
    Route::get('/geo/states', [App\Http\Controllers\Restaurant\GeoController::class, 'states'])->name('geo.states');
    Route::get('/geo/cities', [App\Http\Controllers\Restaurant\GeoController::class, 'cities'])->name('geo.cities');
    Route::post('/geo/resolve', [App\Http\Controllers\Restaurant\GeoController::class, 'resolve'])->name('geo.resolve');

    // Support Tickets (Restaurant Owner)
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [RestaurantTicketController::class, 'index'])->name('index');
        Route::get('create', [RestaurantTicketController::class, 'create'])->name('create');
        Route::post('/', [RestaurantTicketController::class, 'store'])->name('store');
        Route::get('{ticket}', [RestaurantTicketController::class, 'show'])->name('show');
        Route::post('{ticket}/reply', [RestaurantTicketController::class, 'reply'])->name('reply');
        Route::post('{ticket}/close', [RestaurantTicketController::class, 'close'])->name('close');
        Route::post('{ticket}/reopen', [RestaurantTicketController::class, 'reopen'])->name('reopen');
        Route::post('{ticket}/rate', [RestaurantTicketController::class, 'rate'])->name('rate');
    });
});

// Delivery Partner Panel
Route::middleware('role:delivery_partner')->prefix('delivery-partner')->name('delivery-partner.')->group(function () {
    Route::get('/dashboard', [DeliveryDashboardController::class, 'index'])->name('dashboard');

    Route::get('/earnings', [App\Http\Controllers\DeliveryPartner\EarningsController::class, 'index'])->name('earnings.index');
 Route::get('/transactions', [App\Http\Controllers\DeliveryPartner\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [App\Http\Controllers\DeliveryPartner\TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/withdrawals', [App\Http\Controllers\DeliveryPartner\WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [App\Http\Controllers\DeliveryPartner\WithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [App\Http\Controllers\DeliveryPartner\WithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/orders/available', [App\Http\Controllers\DeliveryPartner\OrderController::class, 'available'])->name('orders.available');
    Route::get('/orders/deliveries', [App\Http\Controllers\DeliveryPartner\OrderController::class, 'deliveries'])->name('orders.deliveries');
    Route::get('/orders/{order}', [App\Http\Controllers\DeliveryPartner\OrderController::class, 'show'])->name('orders.show');
    Route::post('/requests/{deliveryRequest}/accept', [App\Http\Controllers\DeliveryPartner\OrderController::class, 'acceptRequest'])->name('requests.accept');
    Route::post('/requests/{deliveryRequest}/reject', [App\Http\Controllers\DeliveryPartner\OrderController::class, 'rejectRequest'])->name('requests.reject');
    Route::post('/orders/{order}/picked-up', [App\Http\Controllers\DeliveryPartner\OrderController::class, 'pickedUp'])->name('orders.picked-up');
    Route::post('/orders/{order}/out-for-delivery', [App\Http\Controllers\DeliveryPartner\OrderController::class, 'outForDelivery'])->name('orders.out-for-delivery');
    Route::post('/orders/{order}/delivered', [App\Http\Controllers\DeliveryPartner\OrderController::class, 'delivered'])->name('orders.delivered');
    Route::post('/orders/{order}/reject', [App\Http\Controllers\DeliveryPartner\OrderController::class, 'rejectOrder'])->name('orders.reject');

    Route::get('/profile', [App\Http\Controllers\DeliveryPartner\ProfileController::class, 'index'])->name('profile');
    Route::get('/profile-detail', [App\Http\Controllers\DeliveryPartner\ProfileController::class, 'profileDetail'])->name('profile-detail');
    Route::get('/kyc', [App\Http\Controllers\DeliveryPartner\ProfileController::class, 'kyc'])->name('kyc');
    Route::post('/profile', [App\Http\Controllers\DeliveryPartner\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-location', [App\Http\Controllers\DeliveryPartner\ProfileController::class, 'updateLocation'])->name('profile.update-location');
    Route::post('/profile/password', [App\Http\Controllers\DeliveryPartner\ProfileController::class, 'changePassword'])->name('password.update');
    Route::get('/account-settings', [App\Http\Controllers\DeliveryPartner\SettingController::class, 'accountSettings'])->name('account.settings');
    Route::post('/account-settings', [App\Http\Controllers\DeliveryPartner\SettingController::class, 'accountSettingsUpdate'])->name('account.settings.update');

    // Notifications (Delivery Partner)
    Route::get('/notifications', [App\Http\Controllers\DeliveryPartner\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [App\Http\Controllers\DeliveryPartner\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\DeliveryPartner\NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('/notifications/read-all', [App\Http\Controllers\DeliveryPartner\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    Route::post('/kyc/submit', [App\Http\Controllers\DeliveryPartner\ProfileController::class, 'kycSubmit'])->name('kyc.submit');
    Route::match(['get', 'post', 'delete'], '/logout', [LoginController::class, 'logout'])->name('logout');

    // Geo / Location helpers (dependent dropdowns)
    Route::get('/geo/states', [App\Http\Controllers\Restaurant\GeoController::class, 'states'])->name('geo.states');
    Route::get('/geo/cities', [App\Http\Controllers\Restaurant\GeoController::class, 'cities'])->name('geo.cities');

    // Support Tickets (Delivery Partner)
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [DeliveryPartnerTicketController::class, 'index'])->name('index');
        Route::get('create', [DeliveryPartnerTicketController::class, 'create'])->name('create');
        Route::post('/', [DeliveryPartnerTicketController::class, 'store'])->name('store');
        Route::get('{ticket}', [DeliveryPartnerTicketController::class, 'show'])->name('show');
        Route::post('{ticket}/reply', [DeliveryPartnerTicketController::class, 'reply'])->name('reply');
        Route::post('{ticket}/close', [DeliveryPartnerTicketController::class, 'close'])->name('close');
        Route::post('{ticket}/reopen', [DeliveryPartnerTicketController::class, 'reopen'])->name('reopen');
        Route::post('{ticket}/rate', [DeliveryPartnerTicketController::class, 'rate'])->name('rate');
    });
});