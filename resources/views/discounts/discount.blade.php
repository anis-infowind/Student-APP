@extends('layouts.default')

@section('title', 'Discounts')

@section('content')
    <div class="container second">
        <div class="row">
            <a class="btn btn-primary add-discount" href="{{url('/discount/create')}}"><i class="fa fa-plus"> </i> Add Discount</a>
        </div>
        <br />
        <div class="row discount-table">
            <table class="table table-responsive table-bordered">
                <thead>
                    <tr>
                        <th>Sr No.</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Action</th>  
                    </tr>
                </thead>
                <tbody>
                    @if(isset($discounts) && !empty($discounts))
                    @if(count($discounts) > 0)
                    @php $i = 1 @endphp
                    @foreach($discounts['price_rules'] as $discount)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $discount['title'] }}</td>
                        <td>{{ $discount['value_type'] }}</td>
                        <td>  
                            
                            
                            <a class="edit-row" href="{{url('discount/edit/'.$discount['id'])}}"><i class="fa fa-edit"></i></a> 
                            <a class="delete-row rule-delete" href="javascript:void(0)" data-rule-id="{{$discount['id']}}" class="rule-delete" data-toggle="modal"><i class="fa fa-trash"></i></a>
                            
                        </td> 
                    </tr>
                    @php $i++ @endphp
                    @endforeach
                    @endif
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
        var AppBridge = window['app-bridge'];
        var actions = AppBridge.actions;
        var TitleBar = actions.TitleBar;
        var Button = actions.Button;
        var Redirect = actions.Redirect;
        var titleBarOptions = {
            title: 'Discounts',
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);

    </script>
@endsection