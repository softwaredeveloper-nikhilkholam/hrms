<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StoreProduct;
use App\StoreCategory;
use App\StoreSubCategory;
use App\AssetProduct;
use App\AssetProductList;
use App\ContactusLandPage;
use App\BranchStock;
use App\StoreBranchOutward;
use App\StoreBranchOutwardProdList;
use Auth;
use Image;

class SubStoresController extends Controller
{
    public function index()
    {
        // Count deactivated products (status = 0)
        $dProductCount = AssetProduct::where('status', 0)->count();

        // Count active products (status = 1)
        $productCount = AssetProduct::where('status', 1)->count();

        // Retrieve the list of active products, joining with categories to get the category name
        $products = AssetProduct::join('store_categories', 'asset_products.categoryId', '=', 'store_categories.id')
            ->select(
                'store_categories.name as categoryName',
                'asset_products.id',
                'asset_products.productName',
                'asset_products.qty',
                'asset_products.created_at',
                'asset_products.installationDate'
            )
            ->where('asset_products.status', 1)
            ->get();

        // Return the view and pass the retrieved data to it
        return view('storeAdmin.subStores.list')->with([
            'dProductCount' => $dProductCount,
            'productCount' => $productCount,
            'products' => $products
        ]);
    }

    public function create()
    {
        $branchId = Auth::user()->reqBranchId;
        $categoryIds = BranchStock::join('store_products', 'branch_stocks.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->where('branch_stocks.branchId', $branchId)
        ->where('branch_stocks.status', 1)
        ->pluck('store_products.categoryId');
        $branchName = ContactusLandPage::where('id', $branchId)->value('branchName');
        $categories = StoreCategory::whereIn('id', $categoryIds)->where('active', 1)->orderBy('name')->pluck('name', 'id');
        $subCategories = StoreSubCategory::where('name', '!=', '')->where('active', 1)->orderBy('name')->pluck('name', 'id');
        return view('storeAdmin.subStores.create')->with(['branchName'=>$branchName,'categories'=>$categories,'subCategories'=>$subCategories]);
    }

    public function getProducts($productId)
    {
        $branchId = Auth::user()->reqBranchId;
       return BranchStock::join('store_products', 'branch_stocks.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->select("store_products.id", "store_categories.name as categoryName", "store_sub_categories.name as subCategoryName", 
        "store_products.size","store_products.color", "store_products.returnStatus","store_products.company", "store_products.productRate", 
        "branch_stocks.stock", "store_products.name", 'store_products.productCode', 'store_units.name as unitName')
        ->where('store_products.id', $productId)
        ->where('branch_stocks.branchId', $branchId)
        ->where('branch_stocks.status', 1)
        ->first();
    }

    public function getInOutProductList($categoryId, $subCategoryId)
    {
        $branchId = Auth::user()->reqBranchId;
       return BranchStock::join('store_products', 'branch_stocks.productId', 'store_products.id')
        ->join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->select('store_products.id', 'store_products.name', 'store_products.image','store_products.company', 
        'store_products.color', 'store_products.size', 'store_units.name as unitName')
        ->where('store_products.categoryId', $categoryId)
        ->where('store_products.subCategoryId', $subCategoryId)
        ->where('branch_stocks.branchId', $branchId)
        ->where('branch_stocks.status', 1)
        ->where('branch_stocks.stock', '!=', 0)
        ->orderBy('store_products.name')
        ->get();

    }

    public function store(Request $request)
    {
        // return $request->all();

        $assetProduct = new AssetProduct;
        $assetProduct->productJourney = $request->productJourney;
        $assetProduct->productId = $request->storeProduct;
        $assetProduct->billNumber = $request->billNumber;
        $assetProduct->invoiceNumber = $request->invoiceNumber;
        $assetProduct->branchId = $request->branchId;
        $assetProduct->location = $request->location;
        $assetProduct->departmentId = $request->departmentId;
        $assetProduct->location = $request->locationOfTheProduct;
        $assetProduct->categoryId = $request->categoryId;
        $assetProduct->subCategoryId = $request->subCategoryId;
        $assetProduct->productName = $request->name;
        $assetProduct->companyName = $request->company;
        $assetProduct->color = $request->color;
        $assetProduct->size = $request->size;
        $assetProduct->specificationAboutProduct = $request->specificationAboutProduct;
        $assetProduct->typeOfAsset =$request->typeOfAsset; 
        $assetProduct->details = $request->details;
        $assetProduct->purchaseDate = $request->purchaseDate;
        $assetProduct->installationDate = $request->installationDate;
        $assetProduct->amount = $request->amount;
        $assetProduct->qty = $request->qty;
        if(!empty($request->file('photoUpload')))
        {
            $originalImage= $request->file('photoUpload');
            $Image = date('Ymdhis').'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/storeAdmin/assetProducts/";
            $image->resize(500,500);
            $image->save($originalPath.$Image);
            $assetProduct->productPhoto = $Image;
        }

        $assetProduct->added_by = Auth::user()->username;
        $assetProduct->updated_by = Auth::user()->username;

        if($assetProduct->save())
        {
            for($i=1; $i<=$request->qty; $i++)
            {
                $productList = new AssetProductList;   
                $productList->assetId = $assetProduct->id;
                $productList->productId = $request->storeProduct;
                $productList->assetProductCode = $i.$assetProduct->id.$request->storeProduct.date('Ymdhis');
                $productList->updated_by = Auth::user()->username;
                $productList->save();   
            }
            
            return redirect('/assetProducts/create')->with("success","Asset Product Added successfully...");
        }

    }

    public function getProductDetails($productId)
    {
        return StoreProduct::find($productId);
    }

    public function storeOutward(Request $request)
    {
        $productCount = count($request->productId);     

        if($productCount)
        {
            $outward = new StoreBranchOutward;
            $outward->forDate = $request->issueDate;
            $outward->receiptNo = "Out-".date('Ymdhis');
            $outward->empCode = $request->empCode;
            $outward->branchName = $request->branchId;
            $outward->branchId = ContactusLandPage::where('branchName', $request->branchId)->value('id');
            $outward->updated_by = Auth::user()->username;        

            if($outward->save())
            {
                for($i=0; $i<$productCount; $i++)
                { 
                    $productList = new StoreBranchOutwardProdList;
                    $productList->outwardId = $outward->id;
                    $productList->productId = $request->productId[$i];
                    $productList->qty = $request->qty[$i];
                    $productList->updated_by = Auth::user()->username;
                    if($productList->save())
                    {
                       $branchStock = BranchStock::where('branchId',  $outward->branchId)
                        ->where('productId', $request->productId[$i])
                        ->where('status', 1)
                        ->first();
                        $branchStock->stock = $branchStock->stock - $request->qty[$i];
                        $branchStock->save();
                    }
                }
            }
            return redirect('/getBranchStock')->with("success","Branch Outward Generated successfully..");
        }
        else
        {
            return redirect()->back()->withInput()->with("error","Not selected any Product...");
        }
       
    }

    public function outwardList()
    {
        $branchId = Auth::user()->reqBranchId;
        $list = StoreBranchOutward::where('branchId', $branchId)->get();
      
        return view('storeAdmin.subStores.list')->with(['list'=>$list,'branchId'=>$branchId]);
    }

    public function inward()
    {
        $branchId = Auth::user()->reqBranchId;
        $categories = StoreCategory::where('active', 1)->pluck('name', 'id');
        return view('storeAdmin.subStores.inward')->with(['categories'=>$categories,'branchId'=>$branchId]);
    }
    public function inwList()
    {
        return view('storeAdmin.subStores.inwList');
    }
    public function inwdList()
    {
        return view('storeAdmin.subStores.inwdList');
    }

    public function storeInward(Request $request)
    {
        // return $request->all();
        $rowCount = count($request->productId);
        if($rowCount)
        {
            for($i=0; $i<$rowCount; $i++)
            {
                $branchStock = BranchStock::Where('branchId', 11)->where('productId', $request->productId[$i])->first();
                if(!$branchStock)
                {
                    $branchStock = new BranchStock;
                    $branchStock->stock = $request->inwardQty[$i];
                }
                else
                {
                    $branchStock->stock = $branchStock->stock + $request->inwardQty[$i];
                }

                $branchStock->userId = Auth::user()->id;
                $branchStock->branchId = 11;
                $branchStock->productId = $request->productId[$i];
                $branchStock->updated_by = Auth::user()->username;
                $branchStock->save();
            }

            return redirect('/subStores/inward')->with("success","Product Stock Added successfully...");
        }
        else
        {
            return redirect('/subStores/inward')->with("error","At least 1 product Add...");
        }
    }
}