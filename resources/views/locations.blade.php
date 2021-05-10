@if(isset($locations) && !empty($locations))
@if(count($locations) > 0)
@foreach($locations as $location)
	<?php $location_name = $location['name']; ?>
    <?php $remove_tatem_name = str_replace("TATEM","",$location_name); ?>
	@if(strpos($location['name'], 'Charlottenburg') !== false)
	<li>
		<input class="store_location_address" type="radio" name="store_address" data-branch-id="03" data-id="{{$location['id']}}" data-name="{{$location['name']}}" data-address1="{{$location['address1']}}" data-city="{{$location['city']}}" data-country="{{$location['country']}}" data-zip="{{$location['zip']}}" data-phone="{{$location['phone']}}" data-province="{{$location['province']}}" data-country-code="{{$location['country_code']}}" value="{{$location['id']}}" id="location_{{$location['id']}}">
		<label for="location_{{$location['id']}}">{{$remove_tatem_name}}</label>
	</li>
	@elseif(strpos($location['name'], 'Prenzlauer Berg') !== false)
	<li>
		<input class="store_location_address" type="radio" name="store_address" data-branch-id="04" data-id="{{$location['id']}}" data-name="{{$location['name']}}" data-address1="{{$location['address1']}}" data-city="{{$location['city']}}" data-country="{{$location['country']}}" data-zip="{{$location['zip']}}" data-phone="{{$location['phone']}}" data-province="{{$location['province']}}" data-country-code="{{$location['country_code']}}" value="{{$location['id']}}" id="location_{{$location['id']}}">
		<label for="location_{{$location['id']}}">{{$remove_tatem_name}}</label>
	</li>
	@elseif(strpos($location['name'], 'Frohnau') !== false)
	<li>
		<input class="store_location_address" type="radio" name="store_address" data-branch-id="01" data-id="{{$location['id']}}" data-name="{{$location['name']}}" data-address1="{{$location['address1']}}" data-city="{{$location['city']}}" data-country="{{$location['country']}}" data-zip="{{$location['zip']}}" data-phone="{{$location['phone']}}" data-province="{{$location['province']}}" data-country-code="{{$location['country_code']}}" value="{{$location['id']}}" id="location_{{$location['id']}}">
		<label for="location_{{$location['id']}}">{{$remove_tatem_name}}</label>
	</li>
	@else
	@endif
@endforeach
@endif
@endif