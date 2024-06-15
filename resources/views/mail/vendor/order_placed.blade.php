@extends('layouts.email')

@section('image')
<img src="{{ trim(asset('img/thank-you.png')) }}" alt="logo" class="img-fluid" style="max-width:100px; margin: 0 0 20px; display: block;">
@endsection

@section('title', 'Order Placed Successfully')

@section('greet', 'Hi Valued Vendor,')

@section('message')
Congratulations on your booking! You may find your order information under open orders on your profile page. If you have any questions at all, please don’t hesitate to reach out to us and allow us to help.
@endsection

@section('detail')
<tr>
    <td align="left">
        <table  cellpadding="0" cellspacing="0" style="width:100%;max-width: 100%; background:#ffffff;" >
            <tr>
            <td width="7%"></td>
                <td width="84%">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="width: 100%;">
                        <tr><td height="30" style="height: 30px;"></td></tr>
                        <tr>
                            <td style="font-size:18px;font-weight:600;color: #3073E8;">Order Summary</td>
                        </tr>
                        <tr><td height="20" style="height: 20px;"></td></tr>
                        <tr>
                            <td valign="top">
                                <table cellspacing="0" cellpadding="0" border="0" width="100%" style="width: 100%;">
                                    <tr>
                                        <td valign="top" width="23%"><img src="{{ $data['product']->thumbnailImage->url }}" alt="" width="190" style="max-width: 100%;display: block;"></td>
                                        <td width="5%">&nbsp;</td>
                                        <td valign="top" width="72%">
                                            <table  cellspacing="0" cellpadding="0" border="0" width="100%" style="vertical-align: top;letter-spacing: -0.2px;">
                                                <tr>
                                                    <th width="28%" align="left" style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">Order ID</th>
                                                    <td width="72%" style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;"># {{ $data['order']->id }}</td>
                                                </tr>
                                                <tr>
                                                    <th align="left" style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">Product Name</th>
                                                    <td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">{{ $data['product']->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th align="left" style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">Reservation Date</th>
                                                    <td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">{{ date($global_date_format,strtotime($data['order']->from_date)) }} / {{ date($global_date_format,strtotime($data['order']->to_date)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th align="left" style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">Booking Date</th>
                                                    <td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">{{ date($global_date_format,strtotime($data['order']->order_date)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th align="left" style="color:#333333;font-size:12px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">Shipping Details</th>
                                                    <td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">Customer Pickup</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                </table>
                            </td>
                        </tr>

                    </table>
                </td>
                <td width="7%"></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td style="height: 40px;" colspan="3"></td>
</tr>

<tr>
    <td>
        <table  cellpadding="0" cellspacing="0" style="width:100%;max-width: 100%; background:#ffffff;font-family:Arial,Helvetica,sans-serif;" >
            <tr>
            <td width="7%"></td>
                <td width="84%" bgcolor="F5FAF1" style="background: #F5FAF1;padding-left:25px;padding-right: 25px;border-radius: 20px;">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="width: 100%;background: #F5FAF1;">
                        <tr><td height="30" style="height: 30px;"></td></tr>
                        <tr>
                            <td style="font-size:18px;font-weight:600;color: #76B53D;">Payment Details</td>
                        </tr>
                        <tr><td height="20" style="height: 20px;"></td></tr>
                        <tr>
                            <td valign="top">
                                <table cellspacing="0" cellpadding="0" border="0" width="100%" style="width: 100%;">
                                    <tr>
                                        <td valign="top" width="79%">
                                            <table  cellspacing="0" cellpadding="0" border="0" width="100%" style="vertical-align: top;letter-spacing: -0.2px;">
                                                <tr>
                                                    <th width="32%" align="left" style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">Payment Id</th>
                                                    <td width="68%" style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">{{ $data['transaction']->payment_id }}</td>
                                                </tr>
                                                <tr>
                                                    <th align="left" style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">Transaction Fee</th>
                                                    <td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">${{ $data['order']->customer_transaction_fee_amount }}</td>
                                                </tr>
                                                <tr>
                                                    <th align="left" style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">{{ ucfirst($data['order']->security_option) }}</th>
                                                    <td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">${{ $data['order']->security_option_amount }}</td>
                                                </tr>
                                                <tr>
                                                    <th align="left" style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">Rent</th>
                                                    <td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">${{ $data['order']->total -  ($data['order']->customer_transaction_fee_amount + $data['order']->security_option_amount) }}</td>
                                                </tr>
                                                <tr>
                                                    <th align="left" style="color:#333333;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-bottom: 2px;">Order Total</th>
                                                    <td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;padding-left: 10px;padding-bottom: 2px;">${{ $data['order']->total }}</td>
                                                </tr>
                                                
                                            </table>
                                        </td>
                                        <td align="right" valign="top" width="18%"><img src="{{ asset('img/payment-card.png') }}" alt="" width="127" style="display: block;"></td>
                                        <td width="3%">&nbsp;</td>
                                    </tr>
                                    
                                </table>
                            </td>
                        </tr>
                        <tr><td height="30" style="height: 30px;"></td></tr>

                    </table>
                </td>
                <td  width="7%"></td>
            </tr>
        </table>
    </td>
</tr>

<tr><td height="40px;"></td></tr>
<tr>
    <td align="left">
        <table  cellpadding="0" cellspacing="0" style="width:100%;max-width: 100%; background:#ffffff;">
            <tr>
                <td  width="7%"></td>
                <td  width="84%">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="display: block;font-weight: 700; color:#1372e6;font-family:Arial,Helvetica,sans-serif;text-align:left;font-size: 18px;">Billing Details</td>
                        </tr>
                        <tr><td height="15px;"></td></tr>
                        <tr><td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;">{{ $data['billing_detail']->name }}</td></tr>
                        <tr><td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;">{{ $data['billing_detail']->email }}</td></tr>
                        <tr><td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;">{{ $data['billing_detail']->phone_number }}</td></tr>
                        <tr><td style="color:#777;font-size:13px;font-family:Arial,Helvetica,sans-serif;text-align:left;line-height:24px;">
                        {{ $data['billing_detail']->address1 }},
                        @if (!is_null($data['billing_detail']->address2))
                            {{ $data['billing_detail']->address2 }}, 
                        @endif
                        {{ $data['billing_detail']->postcode }}, {{ ucfirst(@$data['billing_detail']->city->name) }}, {{ ucfirst(@$data['billing_detail']->state->name) }}, {{ ucfirst(@$data['billing_detail']->country->name) }} </td></tr>
                    </table>										
                </td>
                <td  width="7%"></td>
            </tr>
        </table>
    </td>
</tr>

@endsection

@section('wishes', 'With best of wishes,')