<?php

return [
    /*
     * How many minutes a one-time password stays valid.
     */
    'default_expires_in_minutes' => 2,

    /*
     * Delete older one-time passwords when a new one is created.
     */
    'only_one_active_one_time_password_per_user' => true,

    /*
     * Number of digits in the generated one-time password.
     */
    'password_length' => 6,

    /*
     * Rate limit for consuming (verifying) one-time passwords.
     */
    'rate_limit_attempts' => [
        'max_attempts_per_user' => 5,
        'time_window_in_seconds' => 60,
    ],
];
