@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        @foreach($products as $value)
        <div class="col-md-4 mb-2">
            <div class="card" style="width: 18rem;">
                <img src="https://softsmart.co.za/wp-content/uploads/2018/06/image-not-found-1038x576.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ $value->name }}</h5>
                    <h6 class="card-title">{{ $value->sku }}</h6>
                    <h6 class="card-title" id="price{{$value->id}}"></h6>
                    <div>
                        <h6 id="starCount{{ $value->id }}"></h6>
                    </div>
                    <a class="btn btn-primary" href='{{ route("frontend.getGridItem", $value->id) }}'>See more</a>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    <script>
        
        $.get('{{ route("frontend.getAllItems") }}', function(data) {
            $.get('{{ route("reviews.getAllReviews") }}', function(reviews) {
                $.ajax({
                    type: "get",
                    url: '{{ route("home.getConfigData") }}',
                    data: {},
                    dataType: 'json',
                    success: function(configData) {
                        data.forEach(function(product) {
                            let reviewRating = 0;
                            let reviewCounter = 0;
                            reviews.forEach(function(review){
                                if(product.id == review.fk_product){
                                    reviewCounter++;
                                    reviewRating += review.rating;
                                }
                            });
                            reviewRating = (Math.round(reviewRating/reviewCounter));
                            let starCount ='';
                            let fakeStar = '';
                            let counter = 1;
                            for(var i = 1; i < reviewRating+1; i++){
                                starCount += '<i id="star'+counter+'" class="fas fa-star" style="color:brown"></i>';
                                counter++;
                            }
                            for(var i = 1; i < 6-(reviewRating); i++){
                                fakeStar += '<i id="star'+counter+'" class="far fa-star" style="color:brown"></i>';
                                counter++;
                            }
                            if(reviews.length > 0){
                                $('#starCount'+product.id).html(starCount + fakeStar);
                            }else{
                                console.log(fakestar.repeat(5));
                                $('#starCount'+product.id).html(fakeStar.repeat(5));
                            }
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
    </script>
    @endsection