<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Observers\UserObserver;

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
	public function boot()
	{
		if ($this->app->environment('production')) {
			URL::forceScheme('https');
		}
		
		// 新加入user 加一個mission
		User::observe(UserObserver::class);

		Builder::macro('whereLike', function ($columns, $search) {
			$this->where(function ($query) use ($columns, $search) {
				foreach (\Arr::wrap($columns) as $column) {
					$query->orWhere($column, 'LIKE', '%' . $search . '%');
				}
			});

			return $this;
		});
	}
}
