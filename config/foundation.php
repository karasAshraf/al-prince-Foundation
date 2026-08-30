<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Foundation Local Timezone
    |--------------------------------------------------------------------------
    |
    | The operating timezone of the Al-Athar Foundation. Used when admin-entered
    | date-only inputs (e.g. survey start/end dates, news publish date) need to
    | be interpreted as local calendar days rather than UTC.
    |
    | Storage remains UTC throughout the application; this timezone is only
    | applied at input-normalization time (in service layer save methods).
    |
    | Change via .env: FOUNDATION_TIMEZONE=Asia/Riyadh
    |
    */

    'local_timezone' => env('FOUNDATION_TIMEZONE', 'Asia/Riyadh'),

];
