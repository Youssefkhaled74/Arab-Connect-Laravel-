<?php

namespace App\Providers;

use App\Nova\Blog;
use App\Nova\User;
use App\Nova\About;
use App\Nova\Admin;
use App\Nova\Branch;
use App\Nova\Review;
use App\Nova\Contact;
use App\Nova\Country;
use App\Nova\Package;
use App\Nova\Category;

use Laravel\Nova\Nova;

use App\Nova\ActivityLog;
use App\Nova\SubCategory;
use App\Nova\BranchChange;
use App\Nova\PaymentMethod;
use Laravel\Nova\Menu\Menu;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Menu\MenuItem;
// use Laravel\Nova\Fields\Country;
use App\Nova\Admin as NovaAdmin;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Menu\MenuSection;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::make(__('User Management'), [
                    MenuItem::resource(Admin::class),
                    MenuItem::resource(User::class),
                    MenuItem::resource(Branch::class),
                    MenuItem::resource(BranchChange::class),
                ])->icon('users')->collapsable(),

                MenuSection::make(__('Content Management'), [
                    MenuItem::resource(Category::class),
                    MenuItem::resource(SubCategory::class),
                    MenuItem::resource(PaymentMethod::class),
                    MenuItem::resource(About::class),
                    MenuItem::resource(Blog::class),
                    MenuItem::resource(Contact::class),
                    MenuItem::resource(Review::class),
                    MenuItem::externalLink('Permissions', '/dashboard/resources/permissions')
                        ->canSee(fn () => auth()->user()->can('viewAny', Permission::class)),
                    MenuItem::externalLink('Roles', '/dashboard/resources/roles')
                        ->canSee(fn () => auth()->user()->can('viewAny', Role::class)),
                    MenuItem::resource(Country::class),
                ])->icon('file-text')->collapsable(),

                MenuSection::make(__('Logs & Packages'), [
                    MenuItem::resource(ActivityLog::class),
                    MenuItem::resource(Package::class),
                ])->icon('archive')->collapsable(),

                MenuSection::make(__('General Settings'), [
                    MenuSection::make(__('Settings'))
                        ->path('nova-settings/general'),
                ])->icon('cog')->collapsable(),

            ];
        });
        \Outl1ne\NovaSettings\NovaSettings::addSettingsFields([
            Number::make(__('Phone One'), 'phone_one')->rules('required', 'max:255'),
            Number::make(__('Phone Two'), 'phone_two')->rules('nullable', 'max:255'),
            Number::make(__('Phone Three'), 'phone_three')->rules('nullable', 'max:255'),
            Number::make(__('Phone Four'), 'phone_four')->rules('nullable', 'max:255'),
            Number::make(__('Phone Five'), 'phone_five')->rules('nullable', 'max:255'),
			Text::make(__('Email'), 'email')->rules('required', 'max:255', 'email'),
            Text::make(__('Facebook'), 'facebook')->rules('nullable', 'max:255','url'),
            Text::make(__('Twitter'), 'twitter')->rules('nullable', 'max:255','url'),
            Text::make(__('Instagram'), 'instagram')->rules('nullable', 'max:255','url'),
            Text::make(__('YouTube'), 'youtube')->rules('nullable', 'max:255','url'),
            Text::make(__('Pinterest'), 'pinterest')->rules('nullable', 'max:255','url'),
            Text::make(__('Play Store Link'), 'play_store_link')->rules('required', 'max:255','url'),
            Text::make(__('App Store Link'), 'app_store_link')->rules('required', 'max:255','url'),
            Text::make(__('Mobile App Title'), 'mobile_app_title')->rules('required', 'max:255'),
            Markdown::make(__('Mobile App Description'), 'mobile_app_description')->rules('required'),
            Markdown::make(__('Description Category'), 'description_category')->rules('required'),
            Markdown::make(__('Meta Description'), 'meta_description')->rules('required'),
            Text::make(__('Contact Us Title'), 'contact_us_title')->rules('required'),
            Markdown::make(__('Contact Us Description'), 'contact_us_description')->rules('required'),
        ]);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes();
                // ->withPasswordResetRoutes()
                // ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new \Outl1ne\NovaSettings\NovaSettings,
            new \Badinansoft\LanguageSwitch\LanguageSwitch(),
            new \Sereny\NovaPermissions\NovaPermissions(),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
