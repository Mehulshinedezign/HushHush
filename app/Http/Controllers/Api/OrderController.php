<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisputeRequest;
use App\Models\DisputeOrder;
use App\Models\Order;
use App\Models\OrderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class OrderController extends Controller
{

public function uploadRetailerImages(Request $request, $id, $type)
{
    try {
        // Log the entire request to see what is being sent from the frontend
        // Log::info('Received request for uploadRetailerImages', [
        //     'request_data' => $request->all(),
        //     'user_id' => auth()->id(),
        //     'order_id' => $id,
        //     'type' => $type,
        // ]);

        // Validate the uploaded images
        $request->validate([
            'images.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // Check for valid type
        if (!in_array($type, ['pickedup', 'returned'])) {
            // Log::warning('Invalid type provided', ['type' => $type]);
            return response()->json([
                'status' => false,
                'message' => 'Invalid type provided',
                'data' => [],
            ], 400);
        }

        $orderImages = [];

        // Check if files are present and handle the upload
        if ($request->hasFile('images')) {
            foreach ($request->images as $image) {
                // Log each image file before storing
                // Log::info('Processing image', [
                //     'image_name' => $image->getClientOriginalName(),
                //     'image_mime' => $image->getMimeType(),
                // ]);

                // Store the image
                $path = $image->store('order_images', 'public');
                $url = $path;

                // Create the order image record
                $orderImages[] = OrderImage::create([
                    'order_id' => $id,
                    'user_id' => auth()->id(),
                    'file' => $path,
                    'url' => $url,
                    'type' => $type,
                    'uploaded_by' => 'retailer',
                ]);
            }

            // Log the success of the upload process
            // Log::info('Images uploaded successfully', [
            //     'order_id' => $id,
            //     'user_id' => auth()->id(),
            //     'uploaded_images' => $orderImages,
            // ]);

            return response()->json([
                'status' => true,
                'message' => 'Images uploaded successfully',
                'data' => $orderImages,
            ], 201);
        }

        // Log if no files were found in the request
        // Log::warning('No files found in the request', [
        //     'request_data' => $request->all(),
        // ]);

        return response()->json([
            'status' => false,
            'message' => 'Files not found',
            'data' => [],
        ], 400);
    } catch (\Throwable $e) {
        // Log the exception with stack trace
        // Log::error('Error occurred in uploadRetailerImages', [
        //     'error_message' => $e->getMessage(),
        //     'stack_trace' => $e->getTraceAsString(),
        // ]);

        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
            'data' => [
                'errors' => [],
            ],
        ], 500);
    }
}



    public function uploadCustomerImages(Request $request, $id, $type)
    {
        try {
            // dd($type);

            $request->validate([
                'images[].*' => 'required|file|mimes:jpeg,png,jpg,gif,svg',
            ]);

            $user = auth()->user();


            if (!in_array($type, ['pickedup', 'returned'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid type provided',
                    'data' => [],
                ], 400);
            }


            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    // dd($image);
                    $path = $image->store('order_images', 'public');
                    $url = $path;


                    $orderImages[] = OrderImage::create([
                        'order_id' => $id,
                        'user_id' => $user->id,
                        'file' => $path,
                        'url' => $url,
                        'type' => $type,
                        'uploaded_by' => 'customer',
                    ]);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Images uploaded successfully',
                    'data' => $orderImages,
                ], 201);
            }


            return response()->json([
                'status' => false,
                'message' => 'File not found',
                'data' => [],
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => [],
                ],
            ], 500);
        }
    }



    public function retailerVerifyImage(Request $request, $id, $type)
    {
        try {
            $user = auth()->user();

            if (!in_array($type, ['pickedup', 'returned'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid type provided',
                    'data' => [],
                ], 400);
            }

            $order = Order::find($id);

            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found',
                    'data' => [],
                ], 404);
            }

            // Log::info("Before Update: ", $order->toArray());
            $dateTime = date('Y-m-d H:i:s');

            if ($type == 'pickedup') {
                $order->update(['retailer_confirmed_pickedup' => '1']);

                if ($order->customer_confirmed_pickedup == '1' && $order->status !== 'Picked Up') {
                    $order->update(['status' => 'Picked Up', 'pickedup_date' => $dateTime]);
                }
            }

            if ($type == 'returned') {
                $order->update(['retailer_confirmed_returned' => '1']);

                if ($order->customer_confirmed_returned == '1' && $order->status !== 'Completed') {
                    $order->update(['status' => 'Completed', 'returned_date' => $dateTime]);
                }
            }

            // Log::info("After Update: ", $order->toArray());

            return response()->json([
                'status' => true,
                'message' => 'Image verified successfully',
                'data' => $order,
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error: ", ['message' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => [],
                ],
            ], 500);
        }
    }

    public function customerVerifyImage(Request $request, $id, $type)
    {
        try {
            $user = auth()->user();

            if (!in_array($type, ['pickedup', 'returned'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid type provided',
                    'data' => [],
                ], 400);
            }

            $order = Order::find($id);

            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found',
                    'data' => [],
                ], 404);
            }

            // Log::info("Before Update: ", $order->toArray());
            $dateTime = date('Y-m-d H:i:s');

            if ($type == 'pickedup') {
                $order->update(['customer_confirmed_pickedup' => '1']);

                if ($order->retailer_confirmed_pickedup == '1' && $order->status !== 'Picked Up') {
                    $order->update(['status' => 'Picked Up', 'pickedup_date' => $dateTime]);
                }
            }

            if ($type == 'returned') {
                $order->update(['customer_confirmed_returned' => '1']);

                if ($order->retailer_confirmed_returned == '1' && $order->status !== 'Completed') {
                    $order->update(['status' => 'Completed',  'returned_date' => $dateTime]);
                }
            }

            // Log::info("After Update: ", $order->toArray());

            return response()->json([
                'status' => true,
                'message' => 'Image verified successfully',
                'data' => $order,
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error: ", ['message' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => [],
                ],
            ], 500);
        }
    }

    public function orderDisputeApi(DisputeRequest $request, Order $order)
    {
        try {
            $user = auth()->user();
            $userId = $user->id;
            // dd($order);

            if (in_array($order->status, ['Completed', 'Cancelled'])) {
                return response()->json([
                    'status' => false,
                    'message' => "You cannot raise a dispute for cancelled and completed orders",
                    'data' => [],
                ], 400);
            }

            if (in_array($order->dispute_status, ['Yes', 'Resolved'])) {
                return response()->json([
                    'status' => false,
                    'message' => "You cannot raise a dispute for an already disputed order",
                    'data' => [],
                ], 400);
            }

            $dateTime = now();
            $images = [];

            if (isset($request->images)) {
                foreach ($request->images as $file) {
                    if ($file != null) {
                        $images[] = [
                            'order_id' => $order->id,
                            'user_id' => $userId,
                            'url' => Storage::disk('public')->put('orders/dispute', $file),
                            'file' => $file->getClientOriginalName(),
                            'type' => 'disputed',
                            'uploaded_by' => 'customer',
                        ];
                    }
                }
            }

            if (count($images)) {
                DisputeOrder::create([
                    'subject' => $request->subject,
                    'description' => $request->description,
                    'order_id' => $order->id,
                    'reported_id' => $userId,
                    'reported_by' => 'customer',
                ]);
                OrderImage::insert($images);
                $order->update([
                    'dispute_status' => 'Yes',
                    'dispute_date' => $dateTime,
                    'cancellation_note' => $request->description,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Your dispute was submitted successfully. We will contact you soon.',
                'data' => $order,
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error: ", ['message' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'errors' => [],
                ],
            ], 500);
        }
    }

}
