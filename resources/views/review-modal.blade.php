<div class="modal fade" id="completed-note" tabindex="-1" aria-labelledby="completed-noteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">                        
            <div class="modal-body">
                <div class="cancellation-popup-sec">
                    <div class="popup-head">
                        <h6>Write Review</h6>
                        <button type="button" class="close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="pro-tab-contant-row" id="reviewProduct"></div>
                    <form method="post" id="review">
                        @csrf
                        <div class="feedback-review">
                        <h4>Add Rating</h4>
                            {{-- <div id="half-stars">
                                <div class="rating-group"> 
                                    <label aria-label="1 star" class="rating__label" for="rating2-10"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="rating" id="rating2-10" value="1" type="radio">  
                                    <label aria-label="2 stars" class="rating__label" for="rating2-20"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="rating" id="rating2-20" value="2" type="radio">  
                                    <label aria-label="3 stars" class="rating__label" for="rating2-30"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="rating" id="rating2-30" value="3" type="radio">
                                    <label aria-label="4 stars" class="rating__label" for="rating2-40"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="rating" id="rating2-40" value="4" type="radio">  
                                    <label aria-label="5 stars" class="rating__label" for="rating2-50"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                    <input class="rating__input" name="rating" id="rating2-50" value="5" type="radio">
                                </div>
                            </div> --}}
                            <div class="rating">
                                <div class="stars">
                                    <input type="radio" name="rating" value="5" id="five" class="star">
                                    <label for="five"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="rating" value="4" id="four" class="star">
                                    <label for="four"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="rating" value="3" id="three" class="star">
                                    <label for="three"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="rating" value="2" id="two" class="star">
                                    <label for="two"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="rating" value="1" id="one" class="star">
                                    <label for="one"><i class="fa fa-star"></i></label>
                                </div>
                            </div>
                        </div>
                        <textarea class="form-control" name="review" rows="5" placeholder="Please write product review here...."></textarea>
                        <p class="popup-p">By submitting review you give us consent to publish and process personal information in accordance with Term of use and Privacy Policy</p>
                        <button type="submit" class="primary-btn width-full submit">Submit&nbsp;<i class="fa-solid fa-circle-notch fa-spin show-loader" style="display:none;"></i></button>
                    </form>
                </div>
            </div>                       
        </div>
    </div>
</div>