<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\DeliveryPartnerController;
use App\Http\Controllers\Admin\CuisineController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiningOfferController;
use App\Http\Controllers\Admin\EarningsController;
use App\Http\Controllers\Admin\FoodCategoryController;
use App\Http\Controllers\Admin\FoodItemController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\EmailConfigurationController;
use App\Http\Controllers\Admin\SmsConfigurationController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\FoodVariantController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\NightlifeBannerController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\ProfileController;

use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\RestaurantBlogController;
use App\Http\Controllers\Admin\RestaurantOfferController;
use App\Http\Controllers\Admin\RoleController;

use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactMessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
|
| All routes defined here are automatically prefixed with 'admin' and
| assigned the name prefix 'admin.' via bootstrap/app.php.
|
*/

// Guest Admin Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Authenticated Admin Routes
Route::middleware('admin')->group(function () {
    Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Support Tickets Management
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('{ticket}', [TicketController::class, 'show'])->name('show');
        Route::post('{ticket}/reply', [TicketController::class, 'reply'])->name('reply');
        Route::post('{ticket}/status', [TicketController::class, 'updateStatus'])->name('status');
    });

    // Public Contact Inquiries Management
    Route::post('contact-messages/{contact_message}/reply', [ContactMessageController::class, 'reply'])->name('contact-messages.reply');
    Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'update', 'destroy']);

    // Restaurant Management Routes
    Route::patch('restaurants/{restaurant}/toggle-status', [RestaurantController::class, 'toggleStatus'])->name('restaurants.toggle-status');
    Route::post('restaurants/{restaurant}/approval', [RestaurantController::class, 'updateApproval'])->name('restaurants.approval');
    Route::resource('restaurants', RestaurantController::class);

    // Food Category Management Routes
    Route::patch('food-categories/{food_category}/toggle-status', [FoodCategoryController::class, 'toggleStatus'])->name('food-categories.toggle-status');
    Route::post('food-categories/{id}/restore', [FoodCategoryController::class, 'restore'])->name('food-categories.restore');
    Route::delete('food-categories/{id}/force-delete', [FoodCategoryController::class, 'forceDelete'])->name('food-categories.force-delete');
    Route::resource('food-categories', FoodCategoryController::class);

    // Brand Management Routes
    Route::patch('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status');
    Route::post('brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');
    Route::delete('brands/{id}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.force-delete');
    Route::resource('brands', BrandController::class);

    // Nightlife Banner Management Routes
    Route::patch('nightlife-banners/{nightlife_banner}/toggle-status', [NightlifeBannerController::class, 'toggleStatus'])->name('nightlife-banners.toggle-status');
    Route::post('nightlife-banners/{id}/restore', [NightlifeBannerController::class, 'restore'])->name('nightlife-banners.restore');
    Route::delete('nightlife-banners/{id}/force-delete', [NightlifeBannerController::class, 'forceDelete'])->name('nightlife-banners.force-delete');
    Route::resource('nightlife-banners', NightlifeBannerController::class);

    // Promo Code / Campaign Management Routes
    Route::patch('promo-codes/{promo_code}/toggle-status', [PromoCodeController::class, 'toggleStatus'])->name('promo-codes.toggle-status');
    Route::post('promo-codes/campaign/delete', [PromoCodeController::class, 'deleteCampaign'])->name('promo-codes.delete-campaign');
    Route::resource('promo-codes', PromoCodeController::class)->except(['edit', 'update']);

    // Cuisine Management Routes
    Route::patch('cuisines/{cuisine}/toggle-status', [CuisineController::class, 'toggleStatus'])->name('cuisines.toggle-status');
    Route::post('cuisines/{id}/restore', [CuisineController::class, 'restore'])->name('cuisines.restore');
    Route::delete('cuisines/{id}/force-delete', [CuisineController::class, 'forceDelete'])->name('cuisines.force-delete');
    Route::resource('cuisines', CuisineController::class);

    // Food Item Management Routes
    Route::get('restaurants/{restaurant}/foods-ajax', [FoodItemController::class, 'getRestaurantFoods'])->name('restaurants.foods-ajax');
    Route::patch('foods/{food}/toggle-status', [FoodItemController::class, 'toggleStatus'])->name('foods.toggle-status');
    Route::post('foods/{id}/restore', [FoodItemController::class, 'restore'])->name('foods.restore');
    Route::delete('foods/{id}/force-delete', [FoodItemController::class, 'forceDelete'])->name('foods.force-delete');
    Route::resource('foods', FoodItemController::class);

    // Food Variant Management Routes
    Route::patch('food-variants/{food_variant}/toggle-status', [FoodVariantController::class, 'toggleStatus'])->name('food-variants.toggle-status');
    Route::post('food-variants/{id}/restore', [FoodVariantController::class, 'restore'])->name('food-variants.restore');
    Route::delete('food-variants/{id}/force-delete', [FoodVariantController::class, 'forceDelete'])->name('food-variants.force-delete');
    Route::resource('food-variants', FoodVariantController::class);

    // Role Management Routes
    Route::resource('roles', RoleController::class)->except(['show']);

// User Management Routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Payment Transactions Routes
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

    // Delivery Partner (KYC) Management Routes
    Route::get('delivery-partners', [DeliveryPartnerController::class, 'index'])->name('delivery-partners.index');
    Route::get('delivery-partners/{partner}', [DeliveryPartnerController::class, 'show'])->name('delivery-partners.show');
    Route::post('delivery-partners/{partner}/approve', [DeliveryPartnerController::class, 'approve'])->name('delivery-partners.approve');
    Route::post('delivery-partners/{partner}/reject', [DeliveryPartnerController::class, 'reject'])->name('delivery-partners.reject');

    // Restaurant Offer (Today's Deal) Approval Routes
    Route::post('restaurant-offers/{restaurant_offer}/approve', [RestaurantOfferController::class, 'approve'])->name('restaurant-offers.approve');
    Route::post('restaurant-offers/{restaurant_offer}/reject', [RestaurantOfferController::class, 'reject'])->name('restaurant-offers.reject');
    Route::resource('restaurant-offers', RestaurantOfferController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);

    // Dining Offer (Book-a-Table) Approval Routes
    Route::post('dining-offers/{dining_offer}/approve', [DiningOfferController::class, 'approve'])->name('dining-offers.approve');
    Route::post('dining-offers/{dining_offer}/reject', [DiningOfferController::class, 'reject'])->name('dining-offers.reject');
    Route::resource('dining-offers', DiningOfferController::class)->only(['index', 'show']);

    // Restaurant Blogs & Stories Approval Routes
    Route::post('restaurant-blogs/{restaurant_blog}/approve', [RestaurantBlogController::class, 'approve'])->name('restaurant-blogs.approve');
    Route::post('restaurant-blogs/{restaurant_blog}/reject', [RestaurantBlogController::class, 'reject'])->name('restaurant-blogs.reject');
    Route::resource('restaurant-blogs', RestaurantBlogController::class)->only(['index', 'show']);


    // Earnings Routes
    Route::get('earnings/commission', [EarningsController::class, 'commission'])->name('earnings.commission');
    Route::get('earnings/tax', [EarningsController::class, 'tax'])->name('earnings.tax');
    Route::get('earnings/platform', [EarningsController::class, 'platform'])->name('earnings.platform');

    // General Settings Routes
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.index');

    // Email Configuration Routes
    Route::get('email-configuration', [EmailConfigurationController::class, 'edit'])->name('email-configuration.edit');
    Route::post('email-configuration', [EmailConfigurationController::class, 'update'])->name('email-configuration.update');
    Route::post('email-configuration/test', [EmailConfigurationController::class, 'test'])->name('email-configuration.test');

    // SMS Configuration Routes
    Route::get('sms-configuration', [SmsConfigurationController::class, 'edit'])->name('sms-configuration.edit');
    Route::post('sms-configuration', [SmsConfigurationController::class, 'update'])->name('sms-configuration.update');

    // Email Template Management Routes
Route::patch('email-templates/{email_template}/toggle-status', [EmailTemplateController::class, 'toggleStatus'])->name('email-templates.toggle-status');
Route::post('email-templates/layout', [EmailTemplateController::class, 'saveLayout'])->name('email-templates.layout');
Route::resource('email-templates', EmailTemplateController::class);
    // Payment Gateway Configuration Routes
    Route::patch('payment-gateways/{gateway}/toggle-status', [PaymentGatewayController::class, 'toggleStatus'])->name('payment-gateways.toggle-status');
    Route::get('payment-gateways/{gateway}/edit', [PaymentGatewayController::class, 'edit'])->name('payment-gateways.edit');
    Route::put('payment-gateways/{gateway}', [PaymentGatewayController::class, 'update'])->name('payment-gateways.update');
    Route::get('payment-gateways', [PaymentGatewayController::class, 'index'])->name('payment-gateways.index');

    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('account-settings', [SettingController::class, 'accountSettings'])->name('account.settings');
    Route::post('account-settings', [SettingController::class, 'accountSettingsUpdate'])->name('account.settings.update');
});

