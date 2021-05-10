@extends('layouts.default')

@section('title', 'PriceRules')
@section('content')
    <div class="container second">
        <div class="row">
            <a class="btn btn-primary" href="javascript:void(0)">Add Price Rule</a>
        </div>
        <br />
        <div class="row">
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
                    @if(isset($price_rules) && !empty($price_rules))
                    @if(count($price_rules) > 0)
                    @php $i = 1 @endphp
                    @foreach($price_rules['price_rules'] as $price_rule)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $price_rule['title'] }}</td>
                        <td>{{ $price_rule['value_type'] }}</td>
                        <td>
                            <a href="javascript:void(0)"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0)"><i class="fa fa-trash"></i></a>
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