<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VendorPendingProductDataTable;
use App\DataTables\VendorProductDataTable;
use App\Models\Product;

class VendorProductController extends \App\Http\Controllers\Controller
{
    public function index(VendorProductDataTable $dataTable)
    {
        return $dataTable->render('admin.product.seller-product.index');
    }

    public function pending(VendorPendingProductDataTable $dataTable)
    {
        return $dataTable->render('admin.product.seller-pending-product.index');
    }

    public function updateApprove($productId, $approved)
    {
        $product = Product::find($productId);
        $product->is_approved = $approved;
        $product->save();
        toastr()->success('Approve Updated successfully');
        return response(
            [
                'status' => '200',
                'message' =>'Updated Successfully'
            ]
        );
    }


}
