<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Models\UserCard;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;

class CardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // 
        // $user = auth()->user();
        // $selectedCountryId = $user->country_id;
        // if (is_null($user->country_id)) {
        //     $selectedCountryId = Country::where('iso_code', 'US')->pluck('id')->first();
        // }
        // $countries = Country::all();
        // $states = State::where('country_id', $selectedCountryId)->get();
        // $cities = City::where('state_id', $user->state_id)->get();
        // $notAvailable = 'N/A';
        // // 
        // $cards = UserCard::where('user_id', $user->id)->get();

        // $user->getCashier();
        $stripePublicKey = config('cashier.key');
        $stripeCustomer = $user->createOrGetStripeCustomer();
        $intent = $user->createSetupIntent();

        return view('customer.edit.account', compact('user', 'countries', 'states', 'cities', 'selectedCountryId', 'notAvailable', 'cards', 'stripePublicKey', 'stripeCustomer', 'intent'));
    }
    public function create(Request $request)
    {
        $user = Auth::user();
        // $user->getCashier();
        $stripePublicKey = config('cashier.key');
        $stripeCustomer = $user->createOrGetStripeCustomer();
        $intent = $user->createSetupIntent();

        return view('customer.create', compact('intent', 'stripeCustomer', 'stripePublicKey', 'user'));
    }

    public function store(Request $request)
    {

        $user = User::with('cards')->where('id', Auth::user()->id)->get();
        //    dd($user);
        try {
            $request->validate([
                'payment_method' => 'required|string',
                'card_holder_name' => 'required'
            ]);

            $this->saveCard($request);
            $response = ['status' => 'success', 'message' => 'Card has been added successfully'];

            return redirect()->route('edit-account', ["tab" => "nav-card"])->with($response);
        } catch (\Throwable $th) {
            $response = ['status' => 'danger', 'message' => $th->getMessage()];

            return redirect()->back()->withInput()->with($response);
        }
    }
    public function default(Request $request)
    {
        try {
            $this->setDefaultCard($request);

            return response()->json([
                'status' => 'success',
                'message' => 'Your card has been set as default',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->detachCard($request);

            return response()->json([
                'status' => 'success',
                'message' => 'Your card has been deleted successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }
    private function saveCard(Request $request)
    {
        try {
            $user = Auth::user();
            $paymentMethod = $user->addPaymentMethod($request->payment_method);
            $defaultCard = 0;
            if (0 == $user->cards->count()) {
                $defaultCard = 1;
            }

            $user = $user->cards()->updateOrCreate([
                'user_id' => $user->id,
                'fingerprint' => jsencode_userdata($paymentMethod->card->fingerprint),
            ], [
                'card_token' => jsencode_userdata($paymentMethod->id),
                'brand' => $paymentMethod->card->brand,
                'exp_month' => jsencode_userdata($paymentMethod->card->exp_month),
                'exp_year' => jsencode_userdata($paymentMethod->card->exp_year),
                'last_digits' => jsencode_userdata($paymentMethod->card->last4),
                'is_default' => $defaultCard,
            ]);
            return $user;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    private function setDefaultCard(Request $request)
    {
        try {
            $user = Auth::user();
            $card = $user->cards->find($request->card_id);
            // $stripe->customers->update($user->stripe_id, [
            // 'invoice_settings' => ['default_payment_method' => $card->card_token]
            // ]);
            UserCard::where('user_id', $user->id)->where('is_default', 1)->update(['is_default' => 0]);

            return $card->update(['is_default' => 1]);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    private function detachCard(Request $request)
    {
        try {
            $user = Auth::user();
            $card = $user->cards->find($request->card_id);
            $stripe = Cashier::stripe();
            $stripe->paymentMethods->detach($card->card_token);

            return $card->delete();
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }
}
