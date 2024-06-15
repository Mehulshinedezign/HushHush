@extends('layouts.email')

@section('title', '')

@section('greet', "Hello, $user->name")

@section('message')
    Congratulations! Your lease is complete and your payment of {{ $data['orders']['vendor_received_amount'] }} will be
    released.
@endsection

@section('detail')
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0"
                style="width:100%;max-width: 100%; background:#ffffff;font-family:Arial,Helvetica,sans-serif;">
                <tr>
                    <td width="7%"></td>
                    {{-- <td width="84%" bgcolor="F5FAF1"
                        style="background: #F5FAF1;padding-left:25px;padding-right: 25px;border-radius: 20px;">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%"
                            style="width: 100%;background: #F5FAF1;">
                            <tr>
                                <td height="30" style="height: 30px;"></td>
                            </tr>
                            <tr>
                                <td style="font-size:18px;font-weight:600;color: #76B53D;">Payment Details</td>
                            </tr>
                            <tr>
                                <td height="20" style="height: 20px;"></td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <table cellspacing="0" cellpadding="0" border="0" width="100%"
                                        style="width: 100%;">
                                        <tr>
                                            <td valign="top" width="79%">
                                                <table cellspacing="0" cellpadding="0" border="0" width="100%"
                                                    style="vertical-align: top;letter-spacing: -0.2px;">
                                                    @foreach ($data as $order)
                                                        <tr>
                                                            <th width="32%" align="left"
                                                                style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">
                                                                Payment Id</th>
                                                            <td width="68%"
                                                                style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">
                                                                {{ $order->transaction->payment_id }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th align="left"
                                                                style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">
                                                                Transaction Fee</th>
                                                            <td
                                                                style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">
                                                                ${{ $order->customer_transaction_fee_amount }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th align="left"
                                                                style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">
                                                                {{ ucfirst($order->security_option) }}</th>
                                                            <td
                                                                style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">
                                                                ${{ $order->security_option_amount }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th align="left"
                                                                style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">
                                                                Rent</th>
                                                            <td
                                                                style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">
                                                                ${{ $order->total - ($order->customer_transaction_fee_amount + $order->security_option_amount) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th align="left"
                                                                style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">
                                                                Order Total</th>
                                                            <td
                                                                style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">
                                                                ${{ $order->total }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </td>
                                            <td align="right" valign="top" width="18%"><img
                                                    src="{{ asset('img/payment-card.png') }}" alt="" width="127"
                                                    style="display: block;"></td>
                                            <td width="3%">&nbsp;</td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="30" style="height: 30px;"></td>
                            </tr>

                        </table>
                    </td> --}}
                    <td width="7%"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td height="40px;"></td>
    </tr>

@endsection

@section('wishes', 'With love')
