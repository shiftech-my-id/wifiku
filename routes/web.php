<?php

use App\Http\Controllers\App\AuthController;
use App\Http\Controllers\App\CompanyProfileController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\CustomerController;
use App\Http\Controllers\App\ProfileController;
use App\Http\Controllers\App\CostCategoryController;
use App\Http\Controllers\App\CostController;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\App\ProductController;
use App\Http\Controllers\App\UserGroupController;
use App\Http\Middleware\Auth;
use App\Http\Middleware\NonAuthenticated;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage-new');
})->name('home');

Route::get('/test', function () {
    return inertia('Test');
})->name('test');

Route::middleware(NonAuthenticated::class)->group(function () {
    Route::redirect('/', 'app/auth/login', 301);
    // Route::prefix('/app/auth')->group(function () {
    //     Route::match(['get', 'post'], 'login', [AuthController::class, 'login'])->name('app.auth.login');
    //     Route::match(['get', 'post'], 'register', [AuthController::class, 'register'])->name('app.auth.register');
    //     Route::match(['get', 'post'], 'forgot-password', [AuthController::class, 'forgotPassword'])->name('app.auth.forgot-password');
    // });
});

Route::middleware([Auth::class])->group(function () {
    // Route::match(['get', 'post'], 'app/auth/logout', [AuthController::class, 'logout'])->name('app.auth.logout');

    Route::prefix('app')->group(function () {
        Route::redirect('', 'app/dashboard', 301);

        Route::get('dashboard', [DashboardController::class, 'index'])->name('app.dashboard');
        Route::get('test', [DashboardController::class, 'test'])->name('app.test');
        Route::get('about', function () {
            return inertia('app/About');
        })->name('app.about');

        Route::prefix('costs')->group(function () {
            Route::get('', [CostController::class, 'index'])->name('app.cost.index');
            Route::get('data', [CostController::class, 'data'])->name('app.cost.data');
            Route::get('add', [CostController::class, 'editor'])->name('app.cost.add');
            Route::get('edit/{id}', [CostController::class, 'editor'])->name('app.cost.edit');
            Route::get('detail/{id}', [CostController::class, 'detail'])->name('app.cost.detail');
            Route::post('save', [CostController::class, 'save'])->name('app.cost.save');
            Route::post('delete/{id}', [CostController::class, 'delete'])->name('app.cost.delete');
            Route::get('export', [CostController::class, 'export'])->name('app.cost.export');
        });

        Route::prefix('cost-categories')->group(function () {
            Route::get('', [CostCategoryController::class, 'index'])->name('app.cost-category.index');
            Route::get('data', [CostCategoryController::class, 'data'])->name('app.cost-category.data');
            Route::get('add', [CostCategoryController::class, 'editor'])->name('app.cost-category.add');
            Route::get('duplicate/{id}', [CostCategoryController::class, 'duplicate'])->name('app.cost-category.duplicate');
            Route::get('edit/{id}', [CostCategoryController::class, 'editor'])->name('app.cost-category.edit');
            Route::post('save', [CostCategoryController::class, 'save'])->name('app.cost-category.save');
            Route::post('delete/{id}', [CostCategoryController::class, 'delete'])->name('app.cost-category.delete');
            Route::get('export', [CostCategoryController::class, 'export'])->name('app.cost-category.export');
        });

        Route::prefix('customers')->group(function () {
            Route::get('', [CustomerController::class, 'index'])->name('app.customer.index');
            Route::get('data', [CustomerController::class, 'data'])->name('app.customer.data');
            Route::get('add', [CustomerController::class, 'editor'])->name('app.customer.add');
            Route::get('duplicate/{id}', [CustomerController::class, 'duplicate'])->name('app.customer.duplicate');
            Route::get('edit/{id}', [CustomerController::class, 'editor'])->name('app.customer.edit');
            Route::post('save', [CustomerController::class, 'save'])->name('app.customer.save');
            Route::post('delete/{id}', [CustomerController::class, 'delete'])->name('app.customer.delete');
            Route::get('detail/{id}', [CustomerController::class, 'detail'])->name('app.customer.detail');
            Route::get('export', [CustomerController::class, 'export'])->name('app.customer.export');
        });

        Route::prefix('products')->group(function () {
            Route::get('', [ProductController::class, 'index'])->name('app.product.index');
            Route::get('data', [ProductController::class, 'data'])->name('app.product.data');
            Route::get('add', [ProductController::class, 'editor'])->name('app.product.add');
            Route::get('duplicate/{id}', [ProductController::class, 'duplicate'])->name('app.product.duplicate');
            Route::get('edit/{id}', [ProductController::class, 'editor'])->name('app.product.edit');
            Route::post('save', [ProductController::class, 'save'])->name('app.product.save');
            Route::post('delete/{id}', [ProductController::class, 'delete'])->name('app.product.delete');
            Route::get('detail/{id}', [ProductController::class, 'detail'])->name('app.product.detail');
            Route::get('export', [ProductController::class, 'export'])->name('app.product.export');
        });

        Route::prefix('users')->group(function () {
            Route::get('', [UserController::class, 'index'])->name('app.user.index');
            Route::get('data', [UserController::class, 'data'])->name('app.user.data');
            Route::get('add', [UserController::class, 'editor'])->name('app.user.add');
            Route::get('edit/{id}', [UserController::class, 'editor'])->name('app.user.edit');
            Route::get('duplicate/{id}', [UserController::class, 'duplicate'])->name('app.user.duplicate');
            Route::post('save', [UserController::class, 'save'])->name('app.user.save');
            Route::post('delete/{id}', [UserController::class, 'delete'])->name('app.user.delete');
            Route::get('detail/{id}', [UserController::class, 'detail'])->name('app.user.detail');
        });

        Route::prefix('user-groups')->group(function () {
            Route::get('', [UserGroupController::class, 'index'])->name('app.user-group.index');
            Route::get('data', [UserGroupController::class, 'data'])->name('app.user-group.data');
            Route::get('add', [UserGroupController::class, 'editor'])->name('app.user-group.add');
            Route::get('edit/{id}', [UserGroupController::class, 'editor'])->name('app.user-group.edit');
            Route::get('duplicate/{id}', [UserGroupController::class, 'duplicate'])->name('app.user-group.duplicate');
            Route::post('save', [UserGroupController::class, 'save'])->name('app.user-group.save');
            Route::post('delete/{id}', [UserGroupController::class, 'delete'])->name('app.user-group.delete');
            Route::get('detail/{id}', [UserGroupController::class, 'detail'])->name('app.user-group.detail');
        });

        Route::prefix('settings')->group(function () {
            Route::get('profile/edit', [ProfileController::class, 'edit'])->name('app.profile.edit');
            Route::post('profile/update', [ProfileController::class, 'update'])->name('app.profile.update');
            Route::post('profile/update-password', [ProfileController::class, 'updatePassword'])->name('app.profile.update-password');

            Route::get('company-profile/edit', [CompanyProfileController::class, 'edit'])->name('app.company-profile.edit');
            Route::post('company-profile/update', [CompanyProfileController::class, 'update'])->name('app.company-profile.update');

            Route::prefix('users')->group(function () {
                Route::get('', [UserController::class, 'index'])->name('admin.user.index');
                Route::get('data', [UserController::class, 'data'])->name('admin.user.data');
                Route::get('add', [UserController::class, 'editor'])->name('admin.user.add');
                Route::get('edit/{id}', [UserController::class, 'editor'])->name('admin.user.edit');
                Route::get('duplicate/{id}', [UserController::class, 'duplicate'])->name('admin.user.duplicate');
                Route::post('save', [UserController::class, 'save'])->name('admin.user.save');
                Route::post('delete/{id}', [UserController::class, 'delete'])->name('admin.user.delete');
                Route::get('detail/{id}', [UserController::class, 'detail'])->name('admin.user.detail');
                Route::get('export', [UserController::class, 'export'])->name('admin.user.export');
            });

            Route::prefix('user-groups')->group(function () {
                Route::get('', [UserController::class, 'index'])->name('admin.user.index');
                Route::get('data', [UserController::class, 'data'])->name('admin.user.data');
                Route::get('add', [UserController::class, 'editor'])->name('admin.user.add');
                Route::get('edit/{id}', [UserController::class, 'editor'])->name('admin.user.edit');
                Route::get('duplicate/{id}', [UserController::class, 'duplicate'])->name('admin.user.duplicate');
                Route::post('save', [UserController::class, 'save'])->name('admin.user.save');
                Route::post('delete/{id}', [UserController::class, 'delete'])->name('admin.user.delete');
                Route::get('detail/{id}', [UserController::class, 'detail'])->name('admin.user.detail');
                Route::get('export', [UserController::class, 'export'])->name('admin.user.export');
            });
        });
    });
});

require_once __DIR__ . '/auth.php';
