<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\DamageReport;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {

            $menuCounts = [
                'manager' => DamageReport::where('status', 'pending')->count(),

                'admin' => DamageReport::where('status', 'approved_manager')->count(),

                'claim' => DamageReport::where('status', 'waiting_claim_result')->count(),

                'accounting' => DamageReport::where('status', 'waiting_accounting')->count(),

                'sap' => DamageReport::where('status', 'waiting_branch_sap')->count(),

                'hr' => DamageReport::where('status', 'accounting_done')->count(),

                'destroy' => DamageReport::where('status', 'hr_done')
                    ->where('flow_type', 'destroy')->count(),

                'discount' => DamageReport::where('flow_type', 'discount')
                    ->where('status', 'waiting_branch_print')->count(),
            ];

            $view->with('menuCounts', $menuCounts);
        });
    }
}
