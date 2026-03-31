<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Policies\ForumPostPolicy;
use App\Policies\ForumCommentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ForumPost::class => ForumPostPolicy::class,
        ForumComment::class => ForumCommentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
