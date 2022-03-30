<h2>Your auction has been updated</h2>

<hr>

<p>
  Dear {{ $name }},<br><br>
  The auction has changed. Here you go.<br><br>

  @if ($info['year'])
  <b>Year</b>: {{ $info['year'] }}<br>
  @endif

  @if ($info['model'])
  <b>Model</b>: {{ $info['model'] }}<br>
  @endif

  @if ($info['make'])
  <b>Make</b>: {{ $info['make'] }}<br>
  @endif

  @if ($info['mileage'])
  <b>Mileage</b>: {{ $info['mileage'] }}
  @endif
</p>
