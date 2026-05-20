<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\RestockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    const CATEGORIES = [
        'PER' => 'Perhiasan',
        'OLH' => 'Oleh-oleh',
        'BUT' => 'Butiran',
    ];

    const SUBCATEGORIES = [
        'PER' => [
            'CCN' => 'Cincin',
            'KLG' => 'Kalung',
            'GEL' => 'Gelang',
            'ANT' => 'Anting',
            'BRS' => 'Bros',
            'GWG' => 'Giwang',
        ],
        'OLH' => [
            'TAS' => 'Tas',
            'TPI' => 'Topi',
            'UDG' => 'Udeng',
            'PCI' => 'Peci',
            'BJU' => 'Baju',
            'SOU' => 'Souvenir Umum',
        ],
        'BUT' => [
            'LAT' => 'Mutiara Air Laut',
            'TAW' => 'Mutiara Air Tawar',
        ],
    ];

    const PRICE_TIERS = [
        'A' => 'Rp 15.000 – Rp 99.999',
        'B' => 'Rp 100.000 – Rp 499.999',
        'C' => 'Rp 500.000 – Rp 999.999',
        'D' => 'Rp 1.000.000 – Rp 2.999.999',
        'E' => 'Rp 3.000.000 – Rp 9.999.999',
        'F' => 'Rp 10.000.000 – Rp 24.999.999',
        'G' => 'Rp 25.000.000 – Rp 82.000.000',
        'H' => 'Per unit (butiran lepas)',
    ];

    // Map category code → DB value
    const CATEGORY_MAP = [
        'PER' => 'perhiasan',
        'OLH' => 'oleh-oleh',
        'BUT' => 'butiran',
    ];

    const CATEGORY_MAP_REVERSE = [
        'perhiasan' => 'PER',
        'oleh-oleh' => 'OLH',
        'butiran'   => 'BUT',
    ];

    public function index(Request $request)
    {
        $query = Product::withTrashed(false)->with('primaryPhoto');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($request->status === 'nonaktif') {
            $query->where('is_active', false);
        } elseif ($request->status === 'habis') {
            $query->where('stock', 0);
        } elseif ($request->status === 'menipis') {
            $query->whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>', 0);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        $products = $query->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories'    => self::CATEGORIES,
            'subcategories' => self::SUBCATEGORIES,
            'priceTiers'    => self::PRICE_TIERS,
            'subcategoriesJson' => json_encode(self::SUBCATEGORIES),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'category_code'      => 'required|in:PER,OLH,BUT',
            'subcategory_code'   => 'required|string',
            'price_tier'         => 'required|string',
            'price'              => 'required|numeric|min:0',
            'stock'              => 'required|integer|min:0',
            'low_stock_threshold'=> 'nullable|integer|min:1',
            'description'        => 'nullable|string',
            'photos.*'           => 'nullable|image|max:2048',
        ]);

        $categoryCode    = $request->category_code;
        $subcategoryCode = $request->subcategory_code;
        $tier            = $request->price_tier;
        $categoryDb      = self::CATEGORY_MAP[$categoryCode];

        // Generate SKU: [KAT]-[SUBKAT]-[TIER]-[NOMOR URUT]
        $lastProduct = Product::where('sku', 'like', "{$categoryCode}-{$subcategoryCode}-{$tier}-%")
            ->orderBy('id', 'desc')->first();
        $number = $lastProduct
            ? (intval(substr($lastProduct->sku, -4)) + 1)
            : 1;
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

        return redirect()->route('admin.products.index')
            ->with('success', "Produk {$product->name} berhasil ditambahkan! SKU: {$sku}");
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product'           => $product->load('photos'),
            'categories'        => self::CATEGORIES,
            'subcategories'     => self::SUBCATEGORIES,
            'priceTiers'        => self::PRICE_TIERS,
            'subcategoriesJson' => json_encode(self::SUBCATEGORIES),
            'categoryMapReverse'=> self::CATEGORY_MAP_REVERSE,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'price'              => 'required|numeric|min:0',
            'stock'              => 'required|integer|min:0',
            'low_stock_threshold'=> 'nullable|integer|min:1',
            'description'        => 'nullable|string',
            'photos.*'           => 'nullable|image|max:2048',
        ]);

        $product->update([
            'name'                => $request->name,
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

        return redirect()->route('admin.products.index')
            ->with('success', "Produk {$product->name} berhasil diupdate!");
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', "Produk {$product->name} berhasil dihapus!");
    }

    public function restock(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'supplier' => 'nullable|string',
            'notes'    => 'nullable|string',
        ]);

        $stockBefore = $product->stock;
        $stockAfter  = $stockBefore + $request->quantity;

        $product->update(['stock' => $stockAfter, 'is_active' => true]);

        RestockLog::create([
            'product_id'     => $product->id,
            'user_id'        => auth()->id() ?? 1,
            'quantity_added' => $request->quantity,
            'stock_before'   => $stockBefore,
            'stock_after'    => $stockAfter,
            'supplier'       => $request->supplier,
            'notes'          => $request->notes,
        ]);

        return redirect()->back()
            ->with('success', "Stok {$product->name} berhasil ditambah {$request->quantity} item!");
    }

    public function toggleStatus(Product $product)
    {
        if ($product->stock === 0 && !$product->is_active) {
            return redirect()->back()
                ->with('error', 'Produk tidak bisa diaktifkan karena stok kosong!');
        }
        $product->update(['is_active' => !$product->is_active]);
        return redirect()->back()->with('success', "Status produk berhasil diubah!");
    }
}
