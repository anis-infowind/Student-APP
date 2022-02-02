@if(isset($setting) && !empty($setting))
<a class="verify-student-btn btn is--primary" onclick="window.open(this.href, 'StudentVerify', 'width=500,height=950')" target="popup" href="https://studentenrabatt.com/profile/login?identifier={{time()}}&return={{ $shop }}/">{{$setting->meta_value}}</a>
@endif