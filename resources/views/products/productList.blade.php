@foreach($products as $value)
<tr onclick="checkTheCheckbox({{$value -> id}})">
    <td>{{ $value -> id }}</td>
    <td>{{ $value -> name }}</td>
    <td>{{ $value -> sku }}</td>
    @if($value->status == '1')
    <td>Enabled</td>
    @else
    <td>Disabled</td>
    @endif
    <td>{{ $value -> base_price }}</td>
    <td>{{ $value -> special_price }}</td>
    <td><img src='{{ secure_asset("public/images/$value->image") }}' alt='item image' class='img-fluid img-thumbnail' style='max-width: 100px; max-heigth: 100px;'><img /></td>
    <td>{!! $value -> description !!}</td>
    <td><a href="#" class="btn btn-success my-1" onclick="editItem({{$value -> id}})">Edit</a><a href="#" class="btn btn-danger" onclick="deleteItem({{$value -> id}})">Delete</a></td>
    <td hidden><input type="checkbox" class="product_checkbox" name="product_checkbox[]" id="{{$value -> id}}" value="{{$value -> id}}" style="display: none;"></td>
</tr>


@endforeach