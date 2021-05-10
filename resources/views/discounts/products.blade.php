@if(isset($products) && !empty($products))
@if(count($products) > 0)
<div class="product-list">
<select name="product_ids[]" class="form-control" multiple="multiple" style="height:100px">
@foreach($products as $product)
    @if(isset($old_product_ids) && count($old_product_ids))
        @if(in_array($product['id'], $old_product_ids))
        <option value="{{$product['id']}}" selected="selected">{{$product['title']}}</option>
        @else
        <option value="{{$product['id']}}">{{$product['title']}}</option>
        @endif
    @else
    <option value="{{$product['id']}}">{{$product['title']}}</option>
    @endif
@endforeach
</select>
</div>
@endif
@endif