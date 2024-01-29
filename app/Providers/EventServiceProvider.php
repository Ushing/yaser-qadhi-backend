<?php

namespace App\Providers;

use App\Events\HajjProfileCreateEvent;
use App\Listeners\HajjProfileCreateListener;
use App\Models\Dua;
use App\Models\DuaCategory;
use App\Models\DuaSubCategory;
use App\Models\Lecture;
use App\Models\LectureCategory;
use App\Models\LectureSubCategory;
use App\Observers\DuaCategoryObserver;
use App\Observers\DuaObserver;
use App\Observers\DuaSubCategoryObserver;
use App\Observers\LectureCategoryObserver;
use App\Observers\LectureObserver;
use App\Observers\LectureSubCategoryObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        HajjProfileCreateEvent::class => [
            HajjProfileCreateListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Dua::observe(DuaObserver::class);
        Lecture::observe(LectureObserver::class);
        DuaCategory::observe(DuaCategoryObserver::class);
        DuaSubCategory::observe(DuaSubCategoryObserver::class);
        LectureCategory::observe(LectureCategoryObserver::class);
        LectureSubCategory::observe(LectureSubCategoryObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
