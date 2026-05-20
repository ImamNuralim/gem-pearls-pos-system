<?php

namespace App\Http\Controllers;

use App\Models\UploadUser;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function showLogin()
    {
        if (session('upload_user_id')) {
            return redirect()->route('upload.create');
        }
        return view('upload.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = UploadUser::where('email', $request->email)
            ->where('is_active', true)
            ->first();

        if (!$user || !$user->checkPassword($request->password)) {
            return back()->withErrors(['email' => 'Email atau password salah!']);
        }

        session(['upload_user_id' => $user->id, 'upload_user_name' => $user->name]);
        return redirect()->route('upload.create');
    }

    public function logout()
    {
        session()->forget(['upload_user_id', 'upload_user_name']);
        return redirect()->route('upload.login');
    }

    public function create()
    {
        if (!session('upload_user_id')) {
            return redirect()->route('upload.login');
        }

        return view('upload.create', [
            'categories'        => ProductController::CATEGORIES,
            'subcategories'     => ProductController::SUBCATEGORIES,
            'priceTiers'        => ProductController::PRICE_TIERS,
            'subcategoriesJson' => json_encode(ProductController::SUBCATEGORIES),
        ]);
    }

    public function store(Request $request)
    {
        if (!session('upload_user_id')) {
            return redirect()->route('upload.login');
        }

        $request->validate([
            'name'               => 'required|string|max:255',
            'category_code'      => 'required|in:PER,OLH,BUT',
            'subcategory_code'   => 'required|string',
            'price_tier'         => 'required|string',
            'price'              => 'required|numeric|min:0',
            'stock'              => 'required|integer|min:0',
            'low_stock_threshold'=> 'nullable|integer|min:1',
            'description'        => 'nullable|string',
            'photos.*'           => 'nullable|image|max:5120',
        ]);

        $categoryCode    = $request->category_code;
        $subcategoryCode = $request->subcategory_code;
        $tier            = $request->price_tier;
        $categoryDb      = ProductController::CATEGORY_MAP[$categoryCode];

        $lastProduct = Product::where('sku', 'like', "{$categoryCode}-{$subcategoryCode}-{$tier}-%")
            ->orderBy('id', 'desc')->first();
        $number = $lastProduct ? (intval(substr($lastProduct->sku, -4)) + 1) : 1;
        $sku = "{$categoryCode}-{$subcategoryCode}-{$tier}-" . str_pad($number, 4, '0', STR_PAD_LEFT);

        $product = Product::create([
            'name'                => $request->name,
            'sku'                 => $sku,
            'category'            => $categoryDb,
            'jewelry_type'        => $subcategoryCode,
            'price_tier'          => $tier,
            'price'               => $request->price,
            'stock'               => $request->stock,
            'low_stock_threshold' => $request->low_stock_threshold ?? 3,
            'description'         => $request->description,
            'is_active'           => $request->stock > 0,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $image = \Intervention\Image\ImageManager::withDriver(\Intervention\Image\Drivers\Gd\Driver::class)->read($photo);
                if ($image->width() > 1200) $image->scale(width: 1200);
                $encoded  = $image->toJpeg(80);
                $filename = 'products/' . uniqid() . '.jpg';
                Storage::disk('public')->put($filename, $encoded);
                ProductPhoto::create([
                    'product_id' => $product->id,
                    'photo_path' => $filename,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return back()->with('success', "Produk {$product->name} berhasil ditambahkan! SKU: {$sku}");
    }
}
