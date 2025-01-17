<?php

// if you did php artisan shield:generate –all rember to add these
use App\Models\User;

class Custom{

    // for Article 
    public function publishAny(User $user): bool
    {
        return $user->can('publish_any_blog::article');
    }

}

?>
