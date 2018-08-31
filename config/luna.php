<?php

return [

    /**
     * Prefix for all of Luna's URL routes
     * and at the same time URL for Luna's home page.
     *
     * Default: luna
     */
    'prefix' => 'luna',

    /**
     * Directory name where all of site's Luna files are located.
     *
     * Default: luna
     */
    'directory' => 'Luna',

    /**
     * Middleware applied to all of Luna's routes.
     *
     * Default: ['web', 'auth']
     */
    'middleware' => [
        'web',
        'auth',
    ],

];
