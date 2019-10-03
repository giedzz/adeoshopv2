@extends('layouts.app')
@section('content')
<div>

    <div class="container">
        <!-- Portfolio Item Heading -->
        <h1 class="my-4">{{ $product -> name}}

        </h1>
        <h2><small>SKU: {{ $product -> sku}}</small></h2>
        <!-- Portfolio Item Row -->
        <div class="row">
            <div class="col-md-8">
                <img class="img-fluid" src='https://softsmart.co.za/wp-content/uploads/2018/06/image-not-found-1038x576.jpg' alt="Item picture" style="width: 100%;">
            </div>
            <div class="col-md-4">
                <h3 class="my-3">About item</h3>
                <h6 class="my-3" id="price{{$product->id}}"></h6>
                <p>{!! $product-> description!!}</p>
                <div class="my-3">
                    <div>
                        @for($i = 1; $i < $average_star+1; $i++) <i class="fas fa-star" style="color:brown"></i>
                            @endfor
                            @for($i = 5; $i > $average_star; $i--)
                            <i class="far fa-star" style="color:brown"></i>
                            @endfor
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <div class="row pl-0">
            </div>
            <div class="row pl-0">
                <h3>Reviews: </h3>
                @foreach($reviews as $review)
                <div class="media pl-0 w-100">
                    <div class="media-body my-3 p-2 reviewBody">
                        <h6 class="mt-0">@for($i = 1; $i < $review->rating+1; $i++) <i class="fas fa-star" style="color:brown"></i>
                                @endfor
                                @for($i = 5; $i > $review->rating; $i--)
                                <i class="far fa-star" style="color:brown"></i>
                                @endfor</h6>
                        {{$review->review}}
                    </div>
                </div>
                @endforeach
            </div>
            <br>
            @auth
            <div class="row mt-3">
                <h2 class="mx-auto">Leave a Review</h2>
            </div>
            <div class="row pl-0 mt-3">
                <h5 id="rating">Rate <small>
                        @for($i = 1; $i < $average_star+1; $i++) <i id="star{{$i}}" class="fas fa-star star" style="color:brown"></i>
                            @endfor
                            @for($i = $average_star+1; $i < 6; $i++)
                            <i id="star{{$i}}" class="far fa-star star" style="color:brown"></i>
                            @endfor
                    </small></h5>
            </div>
            <form class=" mx-auto comment-submit-form mainCommentForm" method="POST" id="commentForm" action="{!! route('reviews.submitReview') !!}">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <textarea id="review" name="review" class="form-control" rows="3" required="required" data-error="Please enter review"></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class=""></div>
                    <input type="hidden" name="fk_product" id="fk_product" value="{{ $product -> id}}" />
                    <input type="hidden" name="reviewRating" id="reviewRating" value="" required="required" />
                    <div class="pl-0">
                        <input type="submit" name="submit" class="btn btn-secondary" value="Submit">
                    </div>
                </div>
        </div>
        </form>
        @endauth
    </div>
    <!-- /.row -->


</div>
</div>
<script>
    // $.get('{{ route("frontend.getAllItems") }}', function(data) {
    //             $.ajax({
    //                 type: "get",
    //                 url: '{{ route("home.getConfigData") }}',
    //                 data: {},
    //                 dataType: 'json',
    //                 success: function(configData) {
    //                     data.forEach(function(product) {
    //                         let price = parseFloat(product.base_price);
    //                         if (configData.tax_inclusion == 1) {
    //                             price = price * (1 + (configData.tax_rate / 100));
    //                         }
    //                         if (product.special_price != 0) {
    //                             priceNew = price - product.special_price;
    //                             $('#price' + product.id).html("<span style='text-decoration:line-through;'>" + price.toFixed(2) + " Eur </span>  " + priceNew.toFixed(2) + " Eur");
    //                         } else {
    //                             if (configData.discount_type == 1) {
    //                                 priceNew = price * (1 - (configData.discount_percent / 100));
    //                                 $('#price' + product.id).html("<span style='text-decoration:line-through;'>" + price.toFixed(2) + " Eur </span>  " + priceNew.toFixed(2) + " Eur");
    //                             } else {
    //                                 priceNew = price - configData.discount_fixed;
    //                                 $('#price' + product.id).html("<span style='text-decoration:line-through;'>" + price.toFixed(2) + " Eur </span>  " + priceNew.toFixed(2) + " Eur");
    //                             }

    //                         }
    //                     })
    //                 }
    //             })
    //         });
    $.get('{{ route("frontend.getAllItems") }}', function(data) {
        $.get('{{ route("reviews.getAllReviews") }}', function(reviews) {
            $.ajax({
                type: "get",
                url: '{{ route("home.getConfigData") }}',
                data: {},
                dataType: 'json',
                success: function(configData) {
                    data.forEach(function(product) {
                        // let reviewRating = 0;
                        // let reviewCounter = 0;
                        // reviews.forEach(function(review) {
                        //     if (product.id == review.fk_product) {
                        //         reviewCounter++;
                        //         reviewRating += review.rating;
                        //     }
                        // });
                        // reviewRating = (Math.round(reviewRating / reviewCounter));
                        // let starCount = '';
                        // let fakeStar = '';
                        // let counter = 1;
                        // for (var i = 1; i < reviewRating + 1; i++) {
                        //     starCount += '<i id="star' + counter + '" class="fas fa-star fa-2x" style="color:brown"></i>';
                        //     counter++;
                        // }
                        // for (var i = 1; i < 6 - (reviewRating); i++) {
                        //     fakeStar += '<i id="star' + counter + '" class="far fa-star fa-2x" style="color:brown"></i>';
                        //     counter++;
                        // }
                        // $('#starCount' + product.id).html(starCount + fakeStar);
                        let price = parseFloat(product.base_price);
                        if (configData.tax_inclusion == 1) {
                            price = price * (1 + (configData.tax_rate / 100));
                        }
                        if (product.special_price != 0) {
                            priceNew = price - product.special_price;
                            $('#price' + product.id).html("<span style='text-decoration:line-through;'>" + price.toFixed(2) + " Eur </span>  " + priceNew.toFixed(2) + " Eur");
                        } else {
                            if (configData.discount_type == 1) {
                                priceNew = price * (1 - (configData.discount_percent / 100));
                                $('#price' + product.id).html("<span style='text-decoration:line-through;'>" + price.toFixed(2) + " Eur </span>  " + priceNew.toFixed(2) + " Eur");
                            } else {
                                priceNew = price - configData.discount_fixed;
                                $('#price' + product.id).html("<span style='text-decoration:line-through;'>" + price.toFixed(2) + " Eur </span>  " + priceNew.toFixed(2) + " Eur");
                            }

                        }
                    })
                }
            })
        })
    });
    $(".star").hover(function() {
        if ($(this).hasClass('fas') == true) {
            $(this).addClass('far');
            $(this).removeClass('fas');
        } else {
            $(this).addClass('fas');
            $(this).removeClass('far');
        }
    })
    $(".star").click(function() {
        $reviewValue = (this.id).slice(-1);
        $('#reviewRating').val($reviewValue);
        for($i = $reviewValue; $i < 6; $i++){
            if ($('#star' + $i).hasClass('fas') == true) {
                $('#star' + $i).addClass('far');
                $('#star' + $i).removeClass('fas');
            }
        }
        for($i = 1; $i < $reviewValue; $i++){
            if ($('#star' + $i).hasClass('far') == true) {
                $('#star' + $i).addClass('fas');
                $('#star' + $i).removeClass('far');
            }
        }
        // for ($i = 5; $i > $reviewValue-1; $i--) {
        //     if ($('#star' + $i).hasClass('fas') == true) {
        //         $('#star' + $i).addClass('far');
        //         $('#star' + $i).removeClass('fas');
        //     }
        // }
        // for ($i = 1; $i < $reviewValue+1; $i++) {
        //     if ($('#star' + $i).hasClass('far') == true) {
        //         $('#star' + $i).addClass('fas');
        //         $('#star' + $i).removeClass('far');
        //     }
        // }

    })
</script>
@endsection