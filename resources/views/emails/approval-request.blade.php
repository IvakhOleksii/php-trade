<h2>There is a pending approval waiting</h2>

<hr>

<p>
  Here is the dealer account info.<br><br>

  <b>Name</b>: {{ $user->name }}<br>
  <b>Email</b>: {{ $user->email }}<br>

  @if ($user->dealername)
  <b>Dealer Name</b>: {{ $user->dealername }}<br>
  @endif

  @if ($user->state)
  <b>State</b>: {{ $user->state }}<br>
  @endif

  @if ($user->city)
  <b>City</b>: {{ $user->city }}<br>
  @endif

  @if ($user->address)
  <b>Address</b>: {{ $user->address }}<br>
  @endif

  @if ($user->zip_code)
  <b>Zip Code</b>: {{ $user->zip_code }}<br>
  @endif

  @if ($user->companywebsite)
  <b>Company Website</b>: {{ $user->companywebsite }}<br>
  @endif

  @if ($user->car_make)
  <b>Car Make</b>: {{ $user->car_make }}<br>
  @endif
</p>
