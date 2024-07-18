<?php

namespace App\Http\Controllers;

use App\Models\ProductRating;
use App\Notifications\LenderFirstMsg;
use App\Notifications\RenterFirstMsg;
use Illuminate\Http\Request;
use App\Models\{User, OrderItem, ConversationMedia, Chat, Product};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function storeChat(Request $request)
    {
        try {

            $receiver_id = $request->receiver_id;
            $order_id = jsdecode_userdata($request->order_id);
            $sent_by = auth()->user()->role_id == '3' ? 'Customer' : 'Retailer';

            if (check_chat_exist($receiver_id, $order_id))
                $chat = check_chat_exist($receiver_id, $order_id);
            else
                $chat = auth()->user()->chat()->create(['chatid' => Str::random(10), 'retailer_id' => $receiver_id, 'order_id' => $order_id, 'sent_by' => $sent_by]);

            return response()->json([
                'status' => 'success',
                'message' => "Chat inserted successfully",
                'chat' => ($chat) ? $chat : '',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function userImage(Request $request)
    {
        $user = User::find($request->id);
        return redirect()->away($user->frontend_profile_url);
    }

    public function chatMessages(Request $request)
    {
        // dd($request->toArray());
        try {
            $receiverId = $request->receiverId;
            $orderId = jsdecode_userdata($request->orderId);
            $chatheader = OrderItem::with(['product.thumbnailImage'])->whereOrderId($orderId)->first();
            // dd($chatheader);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'chat retrieved successfully',
                    'data'  => [
                        'chatheader'  =>  view('chat-header', compact('chatheader'))->render(),
                        'getchatId'  => check_chat_exist($receiverId, $orderId) ? check_chat_exist($receiverId, $orderId)->chatid : '',
                    ],
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * send image in chat .
     *
     * @return \Illuminate\Http\Response
     */
    public function chatImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attachment' => 'file|required|mimetypes:image/*|max:' . 1024 * 10
        ], [
            'attachment.mimetypes'  =>  'Please upload valid image',
            'attachment.max'        =>  'Please upload image less that 10Mb.'
        ]);
        try {
            if ($validator->fails())
                throw new \Exception($validator->errors()->first());

            $image = s3_store_image($request->file('attachment'), 'chat/images');
            ConversationMedia::create([
                'chat_id' => $request->Id,
                'name' => $image['name'],
                'url' => $image['url']
            ]);
            return response()->json([
                'status' => true,
                'message' => 'image retrieved successfully',
                'data'  => $image,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function userNames(Request $request)
    {
        $response = User::whereIn('id', $request->user_id)->pluck('name', 'id')->toArray();
        return [
            'data'  =>  $response
        ];
    }

    public function lastchat_update(Request $request)
    {
        // dd($request);
        // dd("receiverId", $request->data[0]['date'], $request->data[0]['receiverId']);    
        if (!$request->data)
            return response()->json([
                'data' => 'msg not updated'
            ], 201);
        // dd($request->data[0]['date']);

        //  Chat::where('chatid', $request->data[0]['chatid'])->update(['last_msg' => $request->data[0]['lastmessage'], 'last_msg_datetime' => $request->data[0]['date']]);
        $chats = Chat::where('chatid', $request->data[0]['chatid'])->first();
        $user = User::with('notification')->where('id', $request->data[0]['receiverId'])->first();
        if ($chats->email_send_count != 2) {

            if ($chats->retailer_id == (int)($request->data[0]['receiverId'])) {
                $this->chatupdate($request, 1);
                if ($chats->email_send_count == 0) {
                    //email;
                    $lender = User::where('id', $chats->user_id)->first();
                    $emaildata = [
                        'lender' => $lender,
                        'chats' => $chats
                    ];
                    // if (@$user->notification->lender_send_renter_first_msg == "on") {
                    $user->notify(new LenderFirstMsg($user, $emaildata));
                    // }
                }
            } else {
                $this->chatupdate($request, 2);
                if ($chats->email_send_count == 1) {
                    //email

                    $renter = User::where('id', $chats->retailer_id)->first();
                    $emaildata = [
                        'renter' => $renter,
                        'chats' => $chats
                    ];
                    // if (@$user->notification->renter_send_lender_first_msg == "on") {
                    $user->notify(new RenterFirstMsg($user, $emaildata));
                    // }
                }
            }
        } else {
            $this->chatupdate($request, 2);
        }


        return response()->json([
            'data' => 'last msg updated'
        ], 200);
    }
    function chatupdate($request, $count)
    {
        Chat::where('chatid', $request->data[0]['chatid'])->update(['last_msg' => $request->data[0]['lastmessage'], 'last_msg_datetime' => $request->data[0]['date'], 'email_send_count' => $count]);
    }
    public function chatSearch($search)
    {
        // dd("hererer", $search);
        try {

            if (auth()->user()->role_id == '3') {
                $getchat = 'chat';
                $role_relation = 'customer';
            } else {
                $getchat = 'getchat';
                $role_relation = 'retailer';
            }
            $chatlist = OrderItem::with(['product', $getchat])->search($search, $role_relation)->get();
            $type = "retailer";
            // dd("herere", $role_relation, $chatlist);

            //dd($chatlist->toArray());

            return response()->json([
                'status'    =>  true,
                'data'      =>  [
                    'chatlist'  =>  view('chatlist', compact('chatlist', 'type'))->render(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'    =>  false,
                'error'      =>  $e->getMessage()
            ]);
        }
    }

    public function get_review($orderId)
    {
        $product = OrderItem::select('id', 'product_id')->with(['product'])->whereOrderId($orderId)->first();
        $rating = ProductRating::select('rating')->where('product_id', $product->product_id)->avg('rating');
        // $rating_number = number_format(round($rating), 1, '.', '');
        $rating_number = number_format(round($rating));
        return response()->json([
            'status'    =>  true,
            'data'      =>  [
                'product'  =>  view('customer.product-review', compact('product', 'rating_number'))->render(),
            ]
        ]);
    }
    public function common_chat(Request $request)
    {
        $chatlist = Chat::where('user_id', auth()->user()->id)->orWhere('retailer_id', auth()->user()->id)->get();

        return view('chat', compact('chatlist'));
    }
}
