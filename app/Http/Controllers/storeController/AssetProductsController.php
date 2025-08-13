<?php

namespace App\Http\Controllers\storeController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StoreProduct;
use App\StoreCategory;
use App\StoreSubCategory;
use App\AssetProduct;
use App\AssetProductList;
use App\Department;
use App\ContactusLandPage;
use App\TempAssetProduct;
use Auth;
use DB;
use Image;
use PDF;

class AssetProductsController extends Controller
{
    public function index()
    {
        $userType = Auth::user()->userType;
        if($userType == '1002' || $userType == '401' || $userType == '201' || $userType == '501' || $userType == '91' || $userType == '801')
        {
            // $assetProducts = TempAssetProduct::all();
            // foreach($assetProducts as $temp)
            // {
            //     $assetProduct = new AssetProduct;
            //     $assetProduct->mainLocation =$temp->mainlocation; 
            //     $assetProduct->productJourney = 2;
            //     $assetProduct->ventureName = $temp->ventures;
            //     $assetProduct->branchId = 11;
            //     $assetProduct->departmentId = 43;
            //     $assetProduct->locationInDepartment = $temp->locationofdepartment;
            //     $assetProduct->productName = $temp->productname;
            //     $assetProduct->category = $temp->productcategory;
            //     $assetProduct->subCategory = $temp->productsubcategory;
            //     $assetProduct->productLocation =$temp->locationoftheproduct; 
            //     $assetProduct->companyName = $temp->company;
            //     $assetProduct->color = $temp->color;
            //     $assetProduct->size = $temp->size;
            //     $assetProduct->typeOfAsset = $temp->typeofproduct;
            //     $assetProduct->purchaseDate = $temp->purchasedate;
            //     $assetProduct->installationDate =$temp->installationdate; 
            //     $assetProduct->qty = $temp->prodqty;
            //     $assetProduct->added_by = Auth::user()->username;
            //     $assetProduct->updated_by = Auth::user()->username;
            //     if($assetProduct->save())
            //     {
            //         $branchShortName = 'AS';
            //         $string = $temp->ventures;
            //         preg_match_all('/\b\w/', $string, $matches);
            //         $firstLetters = implode('', $matches[0]);
    
            //         $tempCode = $firstLetters.'/'.$branchShortName.'/'.$assetProduct->id;
    
            //         for($i=1; $i<=$assetProduct->qty; $i++)
            //         {
            //             $productList = new AssetProductList;   
            //             $productList->assetId = $assetProduct->id;
            //             $productList->assetProductCode = $tempCode.'/'.$i;
            //             $productList->assetProductCodeForQR = date('Ymdhis').$i;
            //             $productList->updated_by = Auth::user()->username;
            //             $productList->save();   
            //         }
            //     }
            // }

            $dProductCount=AssetProduct::where('status', 0)->count();
            $productCount=AssetProduct::where('status', 1)->count();
            $products=AssetProduct::join('departments', 'asset_products.departmentId', 'departments.id')
            ->join('contactus_land_pages', 'asset_products.branchId', 'contactus_land_pages.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'asset_products.productName',
            'asset_products.qty', 'asset_products.created_at','asset_products.ventureName','asset_products.id','asset_products.mainLocation',
            'asset_products.installationDate')
            ->where('asset_products.status', 1)
            ->get();

            return view('storeAdmin.assets.assetProducts.list')->with(['dProductCount'=>$dProductCount,'productCount'=>$productCount,'products'=>$products]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        } 
    }

    public function dlist()
    {
        $userType = Auth::user()->userType;
        if($userType == '1002' || $userType == '401' || $userType == '201' || $userType == '501' || $userType == '91' || $userType == '801')
        {
            $dProductCount=AssetProduct::where('status', 0)->count();
            $productCount=AssetProduct::where('status', 1)->count();
            $products=AssetProduct::join('departments', 'asset_products.departmentId', 'departments.id')
            ->join('contactus_land_pages', 'asset_products.branchId', 'contactus_land_pages.id')
            ->select('contactus_land_pages.branchName', 'departments.name as departmentName', 'asset_products.productName',
            'asset_products.qty', 'asset_products.created_at','asset_products.ventureName','asset_products.id','asset_products.mainLocation',
            'asset_products.installationDate')
            ->where('asset_products.status', 0)
            ->get();

            return view('storeAdmin.assets.assetProducts.dlist')->with(['dProductCount'=>$dProductCount,'productCount'=>$productCount,'products'=>$products]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        } 
    }

    public function create()
    {
        $userType = Auth::user()->userType;
        if($userType == '1002' || $userType == '401' || $userType == '201' || $userType == '501' || $userType == '91' || $userType == '801')
        {
            $dProductCount=AssetProduct::where('status', 0)->count();
            $productCount=AssetProduct::where('status', 1)->count();
            $categories = StoreCategory::where('active', 1)->orderBy('name')->pluck('name', 'id');
            $subCategories = StoreSubCategory::where('name', '!=', '')->where('active', 1)->orderBy('name')->pluck('name', 'id');
            $departments = Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
            $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
            $products = StoreProduct::where('active', 1)->orderBy('name')->pluck('name', 'id');
            return view('storeAdmin.assets.assetProducts.create')->with(['subCategories'=>$subCategories,'products'=>$products,'departments'=>$departments,'branches'=>$branches,'dProductCount'=>$dProductCount,'productCount'=>$productCount,'categories'=>$categories]);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        } 
    }

    public function store(Request $request)
    {
        $userType = Auth::user()->userType;
        if($userType == '1002' || $userType == '401' || $userType == '201' || $userType == '501' || $userType == '91' || $userType == '801')
        {
            $assetProduct = new AssetProduct;
            $assetProduct->productJourney = $request->productJourney;
            $assetProduct->ventureName = $request->ventureName;
            $assetProduct->billNumber = $request->billNumber;
            $assetProduct->invoiceNumber = $request->invoiceNumber;
            $assetProduct->branchId = $request->branchId;
            $assetProduct->departmentId = $request->departmentId;
            $assetProduct->productId = $request->productId;
            $assetProduct->category = $request->category;
            $assetProduct->subCategory = $request->subCategory;
            $assetProduct->mainLocation = $request->mainLocation;
            $assetProduct->locationInDepartment = $request->locationInDepartment;
            $assetProduct->productLocation = $request->productLocation;
            $assetProduct->companyName = $request->companyName;
            $assetProduct->color = $request->color;
            $assetProduct->size = $request->size;
            $assetProduct->specificationProduct = $request->specificationProduct;
            $assetProduct->typeOfAsset = $request->typeOfAsset;
            $assetProduct->purchaseDate = $request->purchaseDate;
            $assetProduct->installationDate =$request->installationDate; 
            $assetProduct->qty = $request->qty;
            $assetProduct->expiryDate = $request->expiryDate;
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
                $branchShortName = ContactusLandPage::where('id', $assetProduct->branchId)->value('shortName');

                $string = $request->ventureName;
                preg_match_all('/\b\w/', $string, $matches);
                $firstLetters = implode('', $matches[0]);

                $tempCode = $firstLetters.'/'.$branchShortName.'/'.$assetProduct->id;

                for($i=1; $i<=$request->qty; $i++)
                {
                    $productList = new AssetProductList;   
                    $productList->assetId = $assetProduct->id;
                    $productList->assetProductCode = $tempCode.'/'.$i;
                    $productList->assetProductCodeForQR = date('Ymdhis').$i;
                    $productList->updated_by = Auth::user()->username;
                    $productList->save();   
                }
                
                return redirect('/assetProducts/create')->with("success","Asset Product Added successfully...");
            }
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        } 

    }

    public function edit($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '1002' || $userType == '401' || $userType == '201' || $userType == '501' || $userType == '91' || $userType == '801')
        {
            $assetProduct=AssetProduct::find($id);
            $productList = AssetProductList::where('assetId',$id)->get();  
 
            $dProductCount=AssetProduct::where('status', 0)->count();
            $productCount=AssetProduct::where('status', 1)->count();
            $categories = StoreCategory::where('active', 1)->orderBy('name')->pluck('name', 'id');
            $subCategories = StoreSubCategory::where('name', '!=', '')->where('active', 1)->orderBy('name')->pluck('name', 'id');
            $departments = Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
            $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
            $products = StoreProduct::where('active', 1)->orderBy('name')->pluck('name', 'id');
            return view('storeAdmin.assets.assetProducts.edit')->with(['assetProduct'=>$assetProduct,'productList'=>$productList,'subCategories'=>$subCategories,'products'=>$products,'departments'=>$departments,'branches'=>$branches,'dProductCount'=>$dProductCount,'productCount'=>$productCount,'categories'=>$categories]); 

        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        } 
    }

    public function update($id, Request $request)
    {
        $assetProduct = AssetProduct::find($id);
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

        $assetProduct->updated_by = Auth::user()->username;
        $assetProduct->save();
        return redirect('/assetProducts')->with("success","Asset Product updated successfully...");
    }

    public function getProductDetails($productId)
    {
        $userType = Auth::user()->userType;
        if($userType == '1002' || $userType == '401' || $userType == '201' || $userType == '501' || $userType == '91' || $userType == '801')
        {
            return StoreProduct::find($productId);
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        } 
    }

    public function deactivate($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '1002' || $userType == '401' || $userType == '201' || $userType == '501' || $userType == '91' || $userType == '801')
        {
            $temp=AssetProduct::find($id);
            $temp->status=0;
            $temp->updated_by=Auth::user()->username;
            if($temp->save())
            {
                AssetProductList::where('assetId', $id)->update(['updated_by'=>Auth::user()->username,'status'=>'0']);
            }

            return redirect('/assetProducts')->with("success","Asset Product Deactivated successfully...");
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        } 
    }

    public function activate($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '1002' || $userType == '401' || $userType == '201' || $userType == '501' || $userType == '91' || $userType == '801')
        {
            $temp=AssetProduct::find($id);
            $temp->status=1;
            $temp->updated_by=Auth::user()->username;
            if($temp->save())
            {
                AssetProductList::where('assetId', $id)->update(['updated_by'=>Auth::user()->username,'status'=>'1']);
            }

            return redirect('/assetProducts')->with("success","Asset Product Activated successfully...");
        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        } 
    }

    public function show($id)
    {
        $userType = Auth::user()->userType;
        if($userType == '1002' || $userType == '401' || $userType == '201' || $userType == '501' || $userType == '91' || $userType == '801')
        {
            $assetProduct=AssetProduct::find($id);
            $productList = AssetProductList::where('assetId',$id)->get();  

            $dProductCount=AssetProduct::where('status', 0)->count();
            $productCount=AssetProduct::where('status', 1)->count();
            $categories = StoreCategory::where('active', 1)->orderBy('name')->pluck('name', 'id');
            $subCategories = StoreSubCategory::where('name', '!=', '')->where('active', 1)->orderBy('name')->pluck('name', 'id');
            $departments = Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
            $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
            $products = StoreProduct::where('active', 1)->orderBy('name')->pluck('name', 'id');
            return view('storeAdmin.assets.assetProducts.show')->with(['assetProduct'=>$assetProduct,'productList'=>$productList,'subCategories'=>$subCategories,'products'=>$products,'departments'=>$departments,'branches'=>$branches,'dProductCount'=>$dProductCount,'productCount'=>$productCount,'categories'=>$categories]); 

        }
        else
        {
            return redirect()->back()->withInput()->with("error","You have not Permission to Access this Page...");
        } 
    }

    public function searchAssetProduct(Request $request)
    {
        $departments = Department::where('active', 1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        $products = StoreProduct::where('active', 1)->orderBy('name')->pluck('name', 'id');
        return view('storeAdmin.assets.assetProducts.searchProduct')->with(['products'=>$products,'departments'=>$departments,'branches'=>$branches]); 
    }

    public function generateQRCodes(Request $request)
    {
        $branchId = $request->branchId;
        $departmentId = $request->departmentId;
        $productId = $request->productId;
        $forDate = $request->forDate;
        $size = $request->size ?? 200;

        $assets = AssetProduct::where('status', 1)
            ->where('branchId', $branchId);

        if (!empty($departmentId)) {
            $assets->where('departmentId', $departmentId);
        }

        if (!empty($forDate)) {
            $assets->whereDate('created_at', $forDate);
        }

        if (!empty($productId)) {
            $assets->where('productId', $productId);
        }

        $assetIds = $assets->pluck('id');

       $productList = AssetProductList::select('id', 'assetProductCode', 'assetProductCodeForQR')
            ->whereIn('assetId', $assetIds)
            ->get();

        $pdf = PDF::loadView('storeAdmin.assets.assetProducts.QRCodePDF', compact('productList', 'size'));

        return $pdf->stream('AssetQRCodes.pdf');
    }

}