<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Bank;
use App\Policies\BankPolicy;
use App\Models\ChequeTemplate;
use App\Models\Cheque;
use App\Policies\ChequePolicy;
use App\Policies\ChequeTemplatePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Bank::class, BankPolicy::class);
        Gate::policy(ChequeTemplate::class, ChequeTemplatePolicy::class);
        Gate::policy(Cheque::class, ChequePolicy::class);
    }
}
