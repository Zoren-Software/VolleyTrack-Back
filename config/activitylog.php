<?php

return [

    /*
     * If set to false, no activities will be saved to the database.
     */
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    /*
     * When the clean-command is executed, all recording activities older than
     * the number of days specified here will be deleted.
     */
    'delete_records_older_than_days' => 365,

    /*
     * If no log name is passed to the activity() helper
     * we use this default log name.
     */
    'default_log_name' => 'default',

    /*
     * You can specify an auth driver here that gets user models.
     * If this is null we'll use the current Laravel auth driver.
     */
    'default_auth_driver' => null,

    /*
     * If set to true, the subject returns soft deleted models.
     */
    'subject_returns_soft_deleted_models' => false,

    /*
     * This model will be used to log activity.
     * It should implement the Spatie\Activitylog\Contracts\Activity interface
     * and extend Illuminate\Database\Eloquent\Model.
     * 
     * Use the MutiActivity model for implementation with the LaravelMultiTenancy package
     */
    'activity_model' => \Spatie\Activitylog\Models\MutiActivity::class,

    /*
     * This is the name of the table that will be created by the migration and
     * used by the Activity model shipped with this package.
     * 
     * The parameter in the configuration must be null so that the 
     * Activity model identifies each table according to its 
     * main tenant log table
     */
    'table_name' => null,

    /*
     * This is the database connection that will be used by the migration and
     * the Activity model shipped with this package. In case it's not set
     * Laravel's database.default will be used instead.
     * 
     * The connection must be the same as the one used in the primary tenant
     */
    'database_connection' => null,
];
