<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class CopyProductImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $product_id;
    public $image_url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product_id, $image_url)
    {
        $this->product_id = $product_id;
        $this->image_url  = $image_url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->product_id . time() . ".jpg";
        Storage::disk('public')->put("products/" . $name, file_get_contents($this->image_url));

        $product = Product::find($this->product_id);
        $product->image_filename = $name;
        $product->save();
    }
}
