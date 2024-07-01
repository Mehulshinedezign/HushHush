@extends('layouts.front')
@section('title', 'My Orders')
@section('content')
    <section class="rental-request-bx">
        <div class="container">
            <div class="rental-request-wrapper">
                <div class="rental-header">
                    <h2>My Order History</h2>
                </div>
                <div class="rental-request-tb mb-4">
                    <div class="order-his-tab-head">
                        <ul class="nav nav-pills " id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
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
                                <button class="nav-link " id="pills-canceled-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-canceled" type="button" role="tab"
                                    aria-controls="pills-canceled" aria-selected="false">
                                    <div class="rental-history-badge danger">
                                        <div class="rental-badge-dot"></div>
                                        <p>Canceled</p>
                                    </div>
                                </button>
                            </li>
                        </ul>
                        <div class="form-group m-0">
                            <div class="formfield">
                                <select name="" id="" class="form">
                                    <option value="">Past 2 Months</option>
                                    <option value="">Past 5 Months</option>
                                    <option value="">Past 1 Year</option>
                                </select>
                                <span class="form-icon">
                                    <img src="{{asset('front/images/dorpdown-icon.svg')}}" alt="dorpdown" width="13">
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab" tabindex="0">
                            <div class="order-his-card-box">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro-0.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn" data-bs-toggle="modal"
                                                    data-bs-target="#cancel-order-Modal">Cancel order</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro1.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Cancel order</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro2.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Cancel order</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro3.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Cancel order</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro4.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Cancel order</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro5.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Cancel order</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro6.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Cancel order</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro7.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Cancel order</a>
                                            </div>
                                        </div>

                                    </div>
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
                            <div class="order-his-card-box">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro-0.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn"
                                                    data-bs-toggle="modal" data-bs-target="#write-review-Modal">Write
                                                    Review</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro1.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Write Review</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro2.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Write Review</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro3.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Write Review</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro4.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Write Review</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro5.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Write Review</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro6.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Write Review</a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="order-his-card">
                                            <div class="order-card-top">
                                                <div class="order-card-img">
                                                    <img src="{{asset('front/images/pro7.png')}}" alt="profile">
                                                </div>
                                                <p>Pennington Dress</p>
                                                <div class="pro-desc-prize">
                                                    <h3>$156</h3>
                                                    <div class="badge day-badge">
                                                        Per day
                                                    </div>

                                                </div>
                                                <div class="order-pro-details">
                                                    <div class="order-details-list">
                                                        <p>Category :</p>
                                                        <h4>Chic Couture , Fashion</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Size:</p>
                                                        <h4>XL</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Rental date:</p>
                                                        <h4>7/31/2023 to 8/01/2023</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Lender:</p>
                                                        <h4>Desirae Vaccaro</h4>
                                                    </div>
                                                    <div class="order-details-list">
                                                        <p>Payment:</p>
                                                        <h4>Paid</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-card-footer">
                                                <a href="#" class="button outline-btn full-btn">Write Review</a>
                                            </div>
                                        </div>

                                    </div>
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
                        <div class="tab-pane fade  " id="pills-canceled" role="tabpanel"
                            aria-labelledby="pills-canceled-tab" tabindex="0">
                            <div class="row g-3">
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="order-his-card">
                                        <div class="order-card-top">
                                            <div class="order-card-img">
                                                <img src="{{asset('front/images/pro-0.png')}}" alt="profile">
                                            </div>
                                            <p>Pennington Dress</p>
                                            <div class="pro-desc-prize">
                                                <h3>$156</h3>
                                                <div class="badge day-badge">
                                                    Per day
                                                </div>

                                            </div>
                                            <div class="order-pro-details">
                                                <div class="order-details-list">
                                                    <p>Category :</p>
                                                    <h4>Chic Couture , Fashion</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Size:</p>
                                                    <h4>XL</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Rental date:</p>
                                                    <h4>7/31/2023 to 8/01/2023</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Lender:</p>
                                                    <h4>Desirae Vaccaro</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Payment:</p>
                                                    <h4>Paid</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="order-his-card">
                                        <div class="order-card-top">
                                            <div class="order-card-img">
                                                <img src="{{asset('front/images/pro1.png')}}" alt="profile">
                                            </div>
                                            <p>Pennington Dress</p>
                                            <div class="pro-desc-prize">
                                                <h3>$156</h3>
                                                <div class="badge day-badge">
                                                    Per day
                                                </div>

                                            </div>
                                            <div class="order-pro-details">
                                                <div class="order-details-list">
                                                    <p>Category :</p>
                                                    <h4>Chic Couture , Fashion</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Size:</p>
                                                    <h4>XL</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Rental date:</p>
                                                    <h4>7/31/2023 to 8/01/2023</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Lender:</p>
                                                    <h4>Desirae Vaccaro</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Payment:</p>
                                                    <h4>Paid</h4>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="order-his-card">
                                        <div class="order-card-top">
                                            <div class="order-card-img">
                                                <img src="{{asset('front/images/pro2.png')}}" alt="profile">
                                            </div>
                                            <p>Pennington Dress</p>
                                            <div class="pro-desc-prize">
                                                <h3>$156</h3>
                                                <div class="badge day-badge">
                                                    Per day
                                                </div>

                                            </div>
                                            <div class="order-pro-details">
                                                <div class="order-details-list">
                                                    <p>Category :</p>
                                                    <h4>Chic Couture , Fashion</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Size:</p>
                                                    <h4>XL</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Rental date:</p>
                                                    <h4>7/31/2023 to 8/01/2023</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Lender:</p>
                                                    <h4>Desirae Vaccaro</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Payment:</p>
                                                    <h4>Paid</h4>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="order-his-card">
                                        <div class="order-card-top">
                                            <div class="order-card-img">
                                                <img src="{{asset('front/images/pro3.png')}}" alt="profile">
                                            </div>
                                            <p>Pennington Dress</p>
                                            <div class="pro-desc-prize">
                                                <h3>$156</h3>
                                                <div class="badge day-badge">
                                                    Per day
                                                </div>

                                            </div>
                                            <div class="order-pro-details">
                                                <div class="order-details-list">
                                                    <p>Category :</p>
                                                    <h4>Chic Couture , Fashion</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Size:</p>
                                                    <h4>XL</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Rental date:</p>
                                                    <h4>7/31/2023 to 8/01/2023</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Lender:</p>
                                                    <h4>Desirae Vaccaro</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Payment:</p>
                                                    <h4>Paid</h4>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="order-his-card">
                                        <div class="order-card-top">
                                            <div class="order-card-img">
                                                <img src="{{asset('front/images/pro4.png')}}" alt="profile">
                                            </div>
                                            <p>Pennington Dress</p>
                                            <div class="pro-desc-prize">
                                                <h3>$156</h3>
                                                <div class="badge day-badge">
                                                    Per day
                                                </div>

                                            </div>
                                            <div class="order-pro-details">
                                                <div class="order-details-list">
                                                    <p>Category :</p>
                                                    <h4>Chic Couture , Fashion</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Size:</p>
                                                    <h4>XL</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Rental date:</p>
                                                    <h4>7/31/2023 to 8/01/2023</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Lender:</p>
                                                    <h4>Desirae Vaccaro</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Payment:</p>
                                                    <h4>Paid</h4>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="order-his-card">
                                        <div class="order-card-top">
                                            <div class="order-card-img">
                                                <img src="{{asset('front/images/pro5.png')}}" alt="profile">
                                            </div>
                                            <p>Pennington Dress</p>
                                            <div class="pro-desc-prize">
                                                <h3>$156</h3>
                                                <div class="badge day-badge">
                                                    Per day
                                                </div>

                                            </div>
                                            <div class="order-pro-details">
                                                <div class="order-details-list">
                                                    <p>Category :</p>
                                                    <h4>Chic Couture , Fashion</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Size:</p>
                                                    <h4>XL</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Rental date:</p>
                                                    <h4>7/31/2023 to 8/01/2023</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Lender:</p>
                                                    <h4>Desirae Vaccaro</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Payment:</p>
                                                    <h4>Paid</h4>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="order-his-card">
                                        <div class="order-card-top">
                                            <div class="order-card-img">
                                                <img src="{{asset('front/images/pro6.png')}}" alt="profile">
                                            </div>
                                            <p>Pennington Dress</p>
                                            <div class="pro-desc-prize">
                                                <h3>$156</h3>
                                                <div class="badge day-badge">
                                                    Per day
                                                </div>

                                            </div>
                                            <div class="order-pro-details">
                                                <div class="order-details-list">
                                                    <p>Category :</p>
                                                    <h4>Chic Couture , Fashion</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Size:</p>
                                                    <h4>XL</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Rental date:</p>
                                                    <h4>7/31/2023 to 8/01/2023</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Lender:</p>
                                                    <h4>Desirae Vaccaro</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Payment:</p>
                                                    <h4>Paid</h4>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="order-his-card">
                                        <div class="order-card-top">
                                            <div class="order-card-img">
                                                <img src="{{asset('front/images/pro7.png')}}" alt="profile">
                                            </div>
                                            <p>Pennington Dress</p>
                                            <div class="pro-desc-prize">
                                                <h3>$156</h3>
                                                <div class="badge day-badge">
                                                    Per day
                                                </div>

                                            </div>
                                            <div class="order-pro-details">
                                                <div class="order-details-list">
                                                    <p>Category :</p>
                                                    <h4>Chic Couture , Fashion</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Size:</p>
                                                    <h4>XL</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Rental date:</p>
                                                    <h4>7/31/2023 to 8/01/2023</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Lender:</p>
                                                    <h4>Desirae Vaccaro</h4>
                                                </div>
                                                <div class="order-details-list">
                                                    <p>Payment:</p>
                                                    <h4>Paid</h4>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
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
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @includeFirst(['validation'])
    @includeFirst(['validation.js_cancel_order'])
    @includeFirst(['validation.js_product_review'])
@endpush
