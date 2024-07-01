@extends('layouts.front')
@section('content')

<section class="rental-request-bx">
    <div class="container">
        <div class="rental-request-wrapper">
            <div class="rental-header">
                <h2>Rental Request</h2>
                <div class="form-group">
                    <div class="formfield">
                        <input type="text" placeholder="Select Date" class="form-control">
                        <span class="form-icon">
                            <img src="{{asset('front/images/calender-icon.svg')}}" alt="img" class="cal-icon">
                        </span>
                    </div>
                </div>
            </div>
            <div class="rental-request-tb mb-4">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true">
                            <div class="rental-history-badge ">
                                <div class="rental-badge-dot"></div>
                                <p>Active</p>
                            </div>

                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-complete-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-complete" type="button" role="tab"
                            aria-controls="pills-complete" aria-selected="false">
                            <div class="rental-history-badge active">
                                <div class="rental-badge-dot"></div>
                                <p>Completed</p>
                            </div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-canceled-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-canceled" type="button" role="tab"
                            aria-controls="pills-canceled" aria-selected="false">
                            <div class="rental-history-badge danger">
                                <div class="rental-badge-dot"></div>
                                <p>Canceled</p>
                            </div>
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab" tabindex="0">
                        <div class="db-table">
                            <div class="tb-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Price</th>
                                            <th>Rental date</th>
                                            <th>Pickup date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-active">Active</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-active">Active</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-active">Active</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-active">Active</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-active">Active</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-active">Active</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-active">Active</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-active">Active</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-active">Active</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-active">Active</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-active">Active</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination-main">
                                <a href="javascript:void(0)" class="pagination-box">
                                    01
                                </a>
                                <a href="javascript:void(0)" class="pagination-box">
                                    02
                                </a>
                                <a href="javascript:void(0)" class="pagination-box active">
                                    03
                                </a>
                                <a href="javascript:void(0)" class="pagination-box">
                                    04
                                </a>
                                <a href="javascript:void(0)" class="pagination-box">
                                    05
                                </a>
                            </div>
                        </div>

                       
                    </div>
                    <div class="tab-pane fade" id="pills-complete" role="tabpanel"
                        aria-labelledby="pills-complete-tab" tabindex="0">
                        <div class="db-table">
                            <div class="tb-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Price</th>
                                            <th>Rental date</th>
                                            <th>Pickup date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-complete">Complete</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-complete">Complete</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-complete">Complete</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-complete">Complete</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-complete">Complete</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-complete">Complete</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-complete">Complete</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-complete">Complete</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-complete">Complete</td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-complete">Complete</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#" class="user-table-profile">
                                                    <div class="table-profile ">
                                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                            width="26" height="27">
                                                    </div>
                                                    <div class="user-table-head">
                                                        <h5>Pennington Dress</h5>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>$855</td>
                                            <td>7/31/2023 to 8/02/2023</td>
                                            <td>15 March, 2023</td>
                                            <td class="user-complete">Complete</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="pagination-main">
                                <a href="javascript:void(0)" class="pagination-box">
                                    01
                                </a>
                                <a href="javascript:void(0)" class="pagination-box">
                                    02
                                </a>
                                <a href="javascript:void(0)" class="pagination-box active">
                                    03
                                </a>
                                <a href="javascript:void(0)" class="pagination-box">
                                    04
                                </a>
                                <a href="javascript:void(0)" class="pagination-box">
                                    05
                                </a>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade bg_light show active" id="pills-canceled" role="tabpanel"
                        aria-labelledby="pills-canceled-tab" tabindex="0">
                        <div class="rental-history-box d-flex">
                            <div class="db-table">
                                <div class="tb-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Price</th>
                                                <th>Rental date</th>
                                                <th>Pickup date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                alt="tb-profile" width="26" height="27">
                                                        </div>
                                                        <div class="user-table-head">
                                                            <h5>Pennington Dress</h5>
                                                        </div>
                                                    </a>

                                                </td>
                                                <td>$855</td>
                                                <td>7/31/2023 to 8/02/2023</td>
                                                <td>15 March, 2023</td>
                                                <td class="user-cancel">Canceled</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                alt="tb-profile" width="26" height="27">
                                                        </div>
                                                        <div class="user-table-head">
                                                            <h5>Pennington Dress</h5>
                                                        </div>
                                                    </a>

                                                </td>
                                                <td>$855</td>
                                                <td>7/31/2023 to 8/02/2023</td>
                                                <td>15 March, 2023</td>
                                                <td class="user-cancel">Canceled</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                alt="tb-profile" width="26" height="27">
                                                        </div>
                                                        <div class="user-table-head">
                                                            <h5>Pennington Dress</h5>
                                                        </div>
                                                    </a>

                                                </td>
                                                <td>$855</td>
                                                <td>7/31/2023 to 8/02/2023</td>
                                                <td>15 March, 2023</td>
                                                <td class="user-cancel">Canceled</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                alt="tb-profile" width="26" height="27">
                                                        </div>
                                                        <div class="user-table-head">
                                                            <h5>Pennington Dress</h5>
                                                        </div>
                                                    </a>

                                                </td>
                                                <td>$855</td>
                                                <td>7/31/2023 to 8/02/2023</td>
                                                <td>15 March, 2023</td>
                                                <td class="user-cancel">Canceled</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                alt="tb-profile" width="26" height="27">
                                                        </div>
                                                        <div class="user-table-head">
                                                            <h5>Pennington Dress</h5>
                                                        </div>
                                                    </a>

                                                </td>
                                                <td>$855</td>
                                                <td>7/31/2023 to 8/02/2023</td>
                                                <td>15 March, 2023</td>
                                                <td class="user-cancel">Canceled</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                alt="tb-profile" width="26" height="27">
                                                        </div>
                                                        <div class="user-table-head">
                                                            <h5>Pennington Dress</h5>
                                                        </div>
                                                    </a>

                                                </td>
                                                <td>$855</td>
                                                <td>7/31/2023 to 8/02/2023</td>
                                                <td>15 March, 2023</td>
                                                <td class="user-cancel">Canceled</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                alt="tb-profile" width="26" height="27">
                                                        </div>
                                                        <div class="user-table-head">
                                                            <h5>Pennington Dress</h5>
                                                        </div>
                                                    </a>

                                                </td>
                                                <td>$855</td>
                                                <td>7/31/2023 to 8/02/2023</td>
                                                <td>15 March, 2023</td>
                                                <td class="user-cancel">Canceled</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                alt="tb-profile" width="26" height="27">
                                                        </div>
                                                        <div class="user-table-head">
                                                            <h5>Pennington Dress</h5>
                                                        </div>
                                                    </a>

                                                </td>
                                                <td>$855</td>
                                                <td>7/31/2023 to 8/02/2023</td>
                                                <td>15 March, 2023</td>
                                                <td class="user-cancel">Canceled</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                alt="tb-profile" width="26" height="27">
                                                        </div>
                                                        <div class="user-table-head">
                                                            <h5>Pennington Dress</h5>
                                                        </div>
                                                    </a>

                                                </td>
                                                <td>$855</td>
                                                <td>7/31/2023 to 8/02/2023</td>
                                                <td>15 March, 2023</td>
                                                <td class="user-cancel">Canceled</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                alt="tb-profile" width="26" height="27">
                                                        </div>
                                                        <div class="user-table-head">
                                                            <h5>Pennington Dress</h5>
                                                        </div>
                                                    </a>

                                                </td>
                                                <td>$855</td>
                                                <td>7/31/2023 to 8/02/2023</td>
                                                <td>15 March, 2023</td>
                                                <td class="user-cancel">Canceled</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                alt="tb-profile" width="26" height="27">
                                                        </div>
                                                        <div class="user-table-head">
                                                            <h5>Pennington Dress</h5>
                                                        </div>
                                                    </a>

                                                </td>
                                                <td>$855</td>
                                                <td>7/31/2023 to 8/02/2023</td>
                                                <td>15 March, 2023</td>
                                                <td class="user-cancel">Canceled</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pagination-main">
                                    <a href="javascript:void(0)" class="pagination-box">
                                        01
                                    </a>
                                    <a href="javascript:void(0)" class="pagination-box">
                                        02
                                    </a>
                                    <a href="javascript:void(0)" class="pagination-box active">
                                        03
                                    </a>
                                    <a href="javascript:void(0)" class="pagination-box">
                                        04
                                    </a>
                                    <a href="javascript:void(0)" class="pagination-box">
                                        05
                                    </a>
                                </div>
                            </div>
                            <div class="rental-history-wrapper">
                                <div class="rental-profile-outer">
                                    <div class="rental-box">
                                        <img src="{{ asset('front/images/table-profile1.png') }}" alt="img" width="100" height="99">
                                    </div>
                                    <div class="rental-profile">
                                        <h3>Pennington Dress</h3>
                                        <p>$156 <span>day</span></p>
                                    </div>
                                </div>
                                <div class="rental-history-date-box">
                                    <div class="form-group">
                                        <label for="">Rental date</label>
                                        <div class="formfield">
                                            <input type="text" placeholder="7/31/2023  to  8/02/2023" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Pickup Date</label>
                                        <div class="formfield">
                                            <input type="text" placeholder="7/31/2023  - 4:30pm" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="rental-hist-pickup">
                                    <h3>Pick up Location</h3>
                                    <p>Akshya Nagar 1st Block 1st Cross, Rammurthy nagar, USA</p>
                                </div>
                                <div class="rental-hist-insurance">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                                    </div>
                                </div>
                                <div class="rental-hist-total-amt">
                                    <h3>Total Amount Paid</h3>
                                    <h3>$322</h3>
                                </div>
                                <div class="rental-his-payment-detail">
                                    <div class="rental-payment-detail">
                                        <h3>Payment Detail</h3>
                                        <div class="rental-payment-detail-card">
                                            <img src="{{asset('front/images/color-card.svg')}}" alt="color-card" width="36" height="26">
                                            <p>Paid By Debit Card No. ********9090</p>
                                        </div>

                                    </div>
                                    <img src="images/check-icon.svg" alt="">
                                </div>
                                <div class="rental-profile-main">
                                    <div class="rental-profile-box">
                                        <div class="rental-profile-head">
                                            <div class="rental-profile-info">
                                                <div class="rental-profile-img-box">
                                                    <img src="{{asset('front/images/profile.png')}}" alt="dp">
                                                </div>
                                                <div class="rental-profile-name">
                                                    <h4>Desirae Vaccaro</h4>
                                                    <div class="rental-profile-rating">
                                                        <a href="#"><i class="fa-solid fa-star"></i></a>
                                                        <a href="#"><i class="fa-solid fa-star"></i></a>
                                                        <a href="#"><i class="fa-solid fa-star"></i></a>
                                                        <a href="#"><i class="fa-solid fa-star"></i></a>
                                                        <a href="#"><i class="fa-solid fa-star"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                           <p> June 11, 2023</p>
                                        </div>
                                        <div class="rental-profile-body">
                                            <p>Safer For The Environment: Our denim factory partner recycles 98% of their water using reverse osmosis filtration and keeps byproducts out of the environment by mixing.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@endpush