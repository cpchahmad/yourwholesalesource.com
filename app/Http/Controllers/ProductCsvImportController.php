<?php

namespace App\Http\Controllers;

use App\Image;
use App\Product;
use App\ProductCsv;
use App\ProductVariant;
use App\Tag;
use App\VariantCsv;
use App\WarehouseInventory;
use Illuminate\Http\Request;

class ProductCsvImportController extends Controller
{
    public function storeVariantFile() {
        $csv_headers = [];
        $csv_items = [];

        if (($handle = fopen(public_path('variants.csv'), 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                if (!$csv_headers)
                    $csv_headers = $row;
                else
                    $csv_items[] = array_combine($csv_headers, $row);
            }
            fclose($handle);
        }



        foreach ($csv_items as $index => $row) {
            $data = array_values($row);


            if(VariantCsv::where('variant_sku', $data[0])->exists()){
                $variant = VariantCsv::where('variant_sku', $data[0])->first();
            }
            else {
                $variant = new VariantCsv();
            }

            $variant->variant_sku = $data[0];
            $variant->ads_price = $data[1];
            $variant->suggested_resale = $data[2];
            $variant->compare_at_price = $data[3];
            $variant->handle = $data[4];
            $variant->body_html = $data[5];
            $variant->option1_name = $data[6];
            $variant->option1_value = $data[7];
            $variant->option2_name = $data[8];
            $variant->option2_value = $data[9];
            $variant->option3_name = $data[10];
            $variant->option3_value = $data[11];
            $variant->title = $data[12];
            $variant->variant_grams = $data[13];
            $variant->variant_quantity = $data[14];
            $variant->variant_barcode = $data[15];
            $variant->seo_title = $data[16];
            $variant->seo_description = $data[17];
            $variant->variant_weight_unit = $data[18];
            $variant->cost_per_item = $data[19];
            $variant->save();
        }
    }

    public function storeProductCsv() {
        $csv_headers = [];
        $csv_items = [];

        if (($handle = fopen(public_path('products.csv'), 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                if (!$csv_headers)
                    $csv_headers = $row;
                else
                    $csv_items[] = array_combine($csv_headers, $row);
            }
            fclose($handle);
        }


        foreach ($csv_items as $index => $row) {
            $data = array_values($row);


            if(ProductCsv::where('variant_sku', $data[12])->exists()){
                continue;
            }
            else {
                $product = new ProductCsv();
            }

            $product->handle = $data[0];
            $product->title = $data[1];
            $product->vendor = $data[2];
            $product->type = $data[3];
            $product->tags = $data[4];
            $product->published = $data[5];
            $product->option1_name = $data[6];
            $product->option1_value = $data[7];
            $product->option2_name = $data[8];
            $product->option2_value = $data[9];
            $product->option3_name = $data[10];
            $product->option3_value = $data[11];
            $product->variant_sku = $data[12];
            $product->variant_price = $data[15];
            $product->variant_compare_at_price = $data[16];
            $product->variant_grams = $data[13];
            $product->variant_quantity = $data[14];
            $product->variant_barcode = $data[17];
            $product->image_src = $data[18];
            $product->image_position = $data[19];
            $product->seo_title = $data[21];
            $product->seo_description = $data[22];
            $product->variant_weight_unit = $data[24];
            $product->variant_image = $data[23];
            $product->cost_per_item = $data[26];
            $product->status = $data[27];
            $product->save();

        }
    }

    public function removeExtraProducts () {
        $products = ProductCsv::whereNotIn('status', [0,1])->get();

        foreach ($products as $product) {
            if(!VariantCsv::where('variant_sku', $product->variant_sku)->exists())
            {
                $product->status = 0;
                $product->save();
            }
            else {
                $product->status = 1;
                $product->save();
            }
        }
    }

    public function processProducts() {
        $variants = VariantCsv::where('cost_per_item', '')->limit(300)->get();

        foreach ($variants as $variant) {
            if($variant->variant_sku != '' && $p = ProductCsv::where('variant_sku', $variant->variant_sku)->first()) {
                $this->createProduct($p, $variant);
                $variant->cost_per_item = 'processed';
                $variant->save();
            }
        }

        dd(123);
    }

    public function createProduct ($p, $variant) {
        if (Product::where('title', $p->title)->exists()) {
            $product = Product::where('title', $p->title)->first();
        } else {
            $product = new Product();
        }
        $product->title = $p->title;
        $product->description = $variant->body_html;
        $product->slug = $p->handle;
        $product->price = $variant->ads_price;
        $product->compare_price = $variant->compare_at_price;
        $product->recommended_price = $variant->suggested_resale;
        $product->cost = $variant->ads_price;
        $product->type = $p->type;
        $product->vendor = $p->vendor;
        $product->quantity = $variant->variant_quantity;
        $product->weight = $variant->variant_grams / 1000;
        $product->sku = $variant->variant_sku;
        $product->barcode = $variant->variant_barcode;
        $product->attribute1 = $variant->option1_name;
        $product->attribute2 = $variant->option2_name;
        $product->attribute3 = $variant->option3_name;
        $product->fulfilled_by = 'Fantasy';
        $product->status = 1;
        $product->variants = 1;

        $product->save();


        try{
            if(getimagesize($p->image_src) !== false)
            {
                $image = file_get_contents($p->image_src);
                $filename = now()->format('YmdHi') .  $p->handle . rand(12321, 456546464) . '.jpg';
                file_put_contents(public_path('images/' . $filename), $image);
                $image = new Image();
                $image->isV = 0;
                $image->position = 1;
                $image->product_id = $product->id;
                $image->image = $filename;
                $image->save();
            }
        }
        catch (\Exception $e) {

        }



        $this->ProductVariants($variant, $product);


        if($p->tags) {
            $tags = explode(',', $p->tags);

            foreach($tags as $tag) {
                if(Tag::where('name', $tag)->exists()) {
                    $t = Tag::where('name', $tag)->first();
                }
                else{
                    $t = new Tag();
                    $t->name = $tag;
                    $t->save();

                }
                $product->tags()->attach($t->id);
            }
        }


        $product->global = 1;
        $product->save();
    }


    public function ProductVariants($data, $product)
    {

        $variants = new  ProductVariant();
        $variants->option1 = $data->option1_value;
        $variants->option2 = $data->option2_value;
        $variants->option3 = $data->option3_value;
        $variants->title = $data->title;
        $variants->price = $data->ads_price;
        $variants->compare_price = $data->compare_at_price;
        $variants->quantity = $data->variant_quantity;
        $variants->cost = $data->ads_price;
        $variants->sku = $data->variant_sku;
        $variants->barcode = $data->variant_barcode;
        $variants->product_id = $product->id;
        if(count($product->has_images) > 0)
            $variants->image = $product->has_images()->first()->id;


        $variants->save();

        $inventory = new WarehouseInventory();
        $inventory->product_variant_id = $variants->id;
        $inventory->warehouse_id = 3;
        $inventory->quantity = $variants->quantity;
        $inventory->save();

    }

}
