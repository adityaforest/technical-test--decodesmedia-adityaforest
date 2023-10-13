<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\PurchaseOrder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\InsertActivity;
use App\Models\DetailOrder;
use App\Models\Material;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');        
        $search = $request->input('search'); // search by order_code or supplier name

        $query = PurchaseOrder::query();

        if($search) {
            $query->where(function ($query) use ($search) {
                $query->where('order_code', 'like', '%' . $search . '%')
                      ->orWhereHas('supplier', function ($query) use ($search) {
                          $query->where('name', 'like', '%' . $search . '%');
                      });
            });
        }

        if($status) {
            $query->where('status', $status );
        }

        $result = $query
            ->with('supplier')
            ->with('detailOrders.material')            
            ->paginate(10);
                                                
        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseOrderRequest $request)
    {        
        try{
            DB::beginTransaction();

            $randomNumber = mt_rand(1000000000, 9999999999);
            $orderCode = 'PO-' . $randomNumber;
        
            $purchaseOrder = PurchaseOrder::create([
                'order_code' => $orderCode,
                'supplier_id' => $request->supplier_id,
                'status' => $request->status
            ]);
            
            foreach($request->orders as $order){
                DetailOrder::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'material_id' => $order['material_id'],
                    'amount' => $order['amount'],
                    'received' => 0,                    
                ]);
            }            

            event(new InsertActivity('purchase_order', $purchaseOrder->id, 'purchase order with code ' . $orderCode , 'purchasing'));

            DB::commit();

            return response()->json(['message' => 'Purchase order created']);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        return $purchaseOrder;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        try{
            DB::beginTransaction();                                

            $purchaseOrder->supplier_id = $request->supplier_id;
            $purchaseOrder->status = 'waiting_approval';
            $purchaseOrder->save();

            //hard delete all previous detail orders
            DetailOrder::where('purchase_order_id', $purchaseOrder->id)->delete();
            
            foreach($request->orders as $order){
                DetailOrder::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'material_id' => $order['material_id'],
                    'amount' => $order['amount'],
                    'received' => 0
                ]);
            }

            event(new InsertActivity('update_order', $purchaseOrder->id, 'update rejected order with code ' . $purchaseOrder->order_code, 'purchasing'));

            DB::commit();

            return response()->json(['message' => 'Purchase order updated']);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }

    public function reject (Request $request, $id){
        try{
            DB::beginTransaction();

            $purchaseOrder = PurchaseOrder::find($id);

            if (!$purchaseOrder) {
                DB::rollBack();
                return response()->json(['message' => 'Order not found'], 404);
            }

            if ($purchaseOrder->status != 'waiting_approval') {
                DB::rollBack();
                return response()->json(['message' => 'Selected order status is not waiting for approval'], 404);
            }

            $purchaseOrder->update(['status' => 'rejected']);

            event(new InsertActivity('reject_order', $id, 'reject order with code ' . $purchaseOrder->order_code, 'admin'));

            DB::commit();

            return response()->json(['message' => 'Purchase order status updated to rejected']);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json(['message' => 'An error occurred'], 500);
        }
        
    }

    public function approve (Request $request, $id){
        try{
            DB::beginTransaction();

            $purchaseOrder = PurchaseOrder::find($id);

            if (!$purchaseOrder) {
                DB::rollBack();
                return response()->json(['message' => 'Order not found'], 404);
            }

            if ($purchaseOrder->status != 'waiting_approval') {
                DB::rollBack();
                return response()->json(['message' => 'Selected order status is not waiting for approval'], 404);
            }

            $purchaseOrder->update(['status' => 'in_progress']);

            event(new InsertActivity('approve_order', $id, 'approve order with code ' . $purchaseOrder->order_code, 'admin'));

            DB::commit();

            return response()->json(['message' => 'Purchase order status updated to in progress']);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json(['message' => 'An error occurred'], 500);
        }
        
    }

    public function receive (Request $request){
        try{
            DB::beginTransaction();

            $purchaseOrderId = '';
            $activityNote = 'received ';

            foreach($request->orders as $order){
                $detailOrderId = $order['detail_id'];
                $detailOrder = DetailOrder::find($detailOrderId);
                $purchaseOrderId = $detailOrder['purchase_order_id'];
    
                if (!$detailOrder) {
                    DB::rollBack();
                    return response()->json(['message' => 'Detail order id' . $detailOrderId . 'not found'], 404);
                }
                
                $receivedAmount = $order['amount'];
                $totalReceivedAmount = $detailOrder['received'] + $receivedAmount;

                //total received amount cannot exceed requested amount when purchasing
                if ($totalReceivedAmount > $detailOrder['amount']) {
                    DB::rollBack();
                    return response()->json(['message' => 'Material id ' . $detailOrder['material_id'] . ' exceed requested amount on purchase order'], 404);
                }

                $detailOrder->received = $totalReceivedAmount;
                $detailOrder->save();
            
                // Add to stock
                $materialId = $detailOrder->material_id;
                $stock = Stock::where('material_id', $materialId)->first();

                if (!$stock) {
                    // If there's no existing stock record, create one
                    Stock::create([
                        'material_id' => $materialId,
                        'stock' => $receivedAmount,
                    ]);
                } else {
                    // Update the existing stock quantity
                    $stock->stock += $receivedAmount;
                    $stock->save();
                }
                
                $materialName = Material::find($detailOrder['material_id'])['name'];
                $activityNote = $activityNote . $receivedAmount . ' ' . $materialName . ', ';
            }

            event(new InsertActivity('receive_order', $purchaseOrderId, $activityNote , 'logistic'));


            DB::commit();

            return response()->json(['message' => 'Success receive order']);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json(['message' => 'An error occurred'], 500);
        }
        
    }
}
