@extends('layouts.email')

@section('title', '')

@section('greet', "Hello, $user->name")

@section('message')
    <p> We are sorry to hear that you would like to cancel Product, but we get it.</p>

    <p>We have confirmed your order cancellation. You will not be charged.</p>

    <p>If you are looking for something else, check out our other listings by clicking below.</p>
    <tr>
        <td colspan="3" align="center" style="padding-top: 15px;">
            {{-- <a href="{{ route('products') }}"
                style="
            text-transform: capitalize;background-color: #1B1B1B;font-size: 16px;color: #fff;display: inline-flex;
            text-align: center;padding: 8px 20px;border-radius: 0;min-height: 40px;align-items: center;transition: all 0.5s;
            min-width: 130px;justify-content: center;border: 1px solid #1B1B1B;text-decoration: none;font-family: 'Inter';
            ">Start
                Chering Now
            </a> --}}
        </td>
    </tr>
@endsection

@section('wishes', 'With best wishes to your next adventure,')
