@if(isset($collections) && !empty($collections))
@if(count($collections) > 0)
<div class="collection-list">
<select name="collection_ids[]" class="form-control" multiple="multiple" style="height:100px">
@foreach($collections as $collection)
    @if(isset($old_collection_ids) && count($old_collection_ids))
        @if(in_array($collection['id'], $old_collection_ids))
        <option value="{{$collection['id']}}" selected="selected">{{$collection['title']}}</option>
        @else
        <option value="{{$collection['id']}}">{{$collection['title']}}</option>
        @endif
    @else
    <option value="{{$collection['id']}}">{{$collection['title']}}</option>
    @endif
@endforeach
</select>
</div>
@endif
@endif