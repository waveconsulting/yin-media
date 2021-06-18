<?php

namespace tanyudii\YinMedia\Services;

use Illuminate\Support\Facades\Route;
use tanyudii\YinMedia\Controllers\MediaController;
use tanyudii\YinMedia\Exceptions\YinMediaException;

class YinMediaService
{
    /**
     * Register media route
     */
    public function routes()
    {
        Route::group(config("yin-media.route", []), function () {
            Route::get("/", [MediaController::class, "index"])->name("index");
            Route::post("/", [MediaController::class, "store"])->name("store");
            Route::get("/{id}", [MediaController::class, "show"])->name("show");
            Route::delete("/{id}", [MediaController::class, "destroy"])->name(
                "destroy"
            );
        });
    }

    /**
     * @param $model
     * @param string $relationName
     */
    public function logUse($model, string $relationName)
    {
        try {
            if ($attachment = $model->$relationName) {
                $logUse = [
                    "entity" => get_class($model),
                    "subject_id" => $model->id,
                ];

                if (
                    !$attachment
                        ->mediaUses()
                        ->where($logUse)
                        ->exists()
                ) {
                    $attachment->mediaUses()->create($logUse);
                }
            }
        } catch (YinMediaException $e) {
            throw $e;
        }
    }
}