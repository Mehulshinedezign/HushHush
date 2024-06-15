<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\User;
use App\Notifications\ContactUs;
use Illuminate\Http\Request;
use Exception, Notification;

class CmsController extends Controller
{
    public function about()
    {
        $page = CmsPage::where('slug', 'about-us')->first();
        
        return view('customer.cms_page', compact('page'));
    }
    public function cms($slug){
        $page = CmsPage::where('slug', $slug)->first();
        if($page->id==8){
            return view('customer.contact_us', compact('page'));
            
        }
        return view('customer.cms_page', compact('page'));

    }

    public function contact(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|min:3|max:50|regex:/^[a-zA-Z]+[a-zA-Z\s]+$/',
                'email' => 'required|email|regex:/^[a-zA-Z]+[a-zA-Z0-9_\.\-]*@[a-zA-Z]+(\.[a-zA-Z]+)*[\.]{1}[a-zA-Z]{2,10}$/i',
                'phone_number' => 'required|regex:/^\([0-9]{3}\)-[0-9]{3}-[0-9]{4}$/',
                'address' => 'required|string',
                'message' => 'required|string',
            ],[
                'name.required' => 'Please enter the name',
                'name.min' => 'Name must be 3-50 characters long',
                'name.max' => 'Name must be 3-50 characters long',
                'name.regex' => 'Name contains alphabets and space only',
                'email.required' => 'Please enter the email',
                'email.email' => 'Invalid email address',
                'email.regex' => 'Invalid email address',
                'phone_number.required' => 'Please enter the phone number',
                'phone_number.regex' => 'Invalid phone number',
                'address.required' => 'Please enter the address',
                'message.required' => 'Please enter the message',
            ]);
            
            $senderData = [
                'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@chere.com'),
                'subject' => env('CONTACT_US_SUBJECT', 'Contact Us!'),
                'form_data' => $request->all(),
                'name' => $request->name,
                'file' => 'mail.admin_contact_us',
                'reply_to' => $request->email,
                'reply_name' => $request->name,
            ];

            $recipientData = [
                'from_address' => env('CONTACT_US_FROM_ADDRESS', 'info@rentahobby.com'),
                'subject' => env('CONTACT_US_SUBJECT', 'Contact Us!'),
                'name' => env('CONTACT_US_NAME', 'Rent A Hobby'),
                'form_data' => $request->all(),
                'file' => 'mail.customer.contact_us',
                'reply_to' => env('CONTACT_US_FROM_ADDRESS', 'info@rentahobby.com'),
                'reply_name' => env('CONTACT_US_NAME', 'Rent A Hobby'),
            ];
            
            try {
                // mail send to site admin
                Notification::route('mail', env('CONTACT_US_FROM_ADDRESS', 'info@rentahobby.com'))->notify(new ContactUs($senderData));
                // mail sent to user
                Notification::route('mail', $request->email)->notify(new ContactUs($recipientData));
                $status = 'success';
                $message = 'Thank you for contacting us. We will contact you soon!';
            } catch (Exception $ex) {
                $status = 'error';
                $message = $ex->getMessage();
            }

            return redirect()->back()->with($status, $message);            
        }
       $admin = User::where('role_id' , 1)->first();
        return view('customer.contact_us', compact('admin'));
    }

    public function policy()
    {
        $page = CmsPage::where('slug', 'privacy-policy')->first();
        
        return view('customer.cms_page', compact('page'));
    }

    public function terms()
    {
        $page = CmsPage::where('slug', 'terms-conditions')->first();
        
        return view('customer.cms_page', compact('page'));
    }

    public function howItWorks()
    {
        $page = CmsPage::where('slug', 'how-it-works')->first();
        
        return view('customer.cms_page', compact('page'));
    }

    public function faq()
    {
        $page = CmsPage::where('slug', 'faq')->first();
        
        return view('customer.cms_page', compact('page'));
    }

    public function toggleStatus(User $user)
    {
        $status = ($user->status == '1') ? '0' : '1';
        $user->update(['status' => $status]);

        return response()->json(['title' => 'Success', 'message' => 'Status changed successfully']);
    }
}
