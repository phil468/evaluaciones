<?php

namespace App\Observers;

use App\Models\Personal;

class PersonalObserver
{
    /**
     * Handle the Personal "created" event.
     *
     * @param  \App\Models\Personal  $personal
     * @return void
     */
    public function created(Personal $personal)
    {
        //
    }

    /**
     * Handle the Personal "updated" event.
     *
     * @param  \App\Models\Personal  $personal
     * @return void
     */
    public function updated(Personal $personal)
    {
        //
    }

    /**
     * Handle the Personal "deleted" event.
     *
     * @param  \App\Models\Personal  $personal
     * @return void
     */
    public function deleted(Personal $personal)
    {
        $personal->update([
            'dni' => time() . '::' . $personal->dni
        ]);
    }

    /**
     * Handle the Personal "restored" event.
     *
     * @param  \App\Models\Personal  $personal
     * @return void
     */
    public function restored(Personal $personal)
    {
        //
    }

    /**
     * Handle the Personal "force deleted" event.
     *
     * @param  \App\Models\Personal  $personal
     * @return void
     */
    public function forceDeleted(Personal $personal)
    {
        //
    }
}
