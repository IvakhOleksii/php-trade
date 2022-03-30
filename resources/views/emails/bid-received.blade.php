<h2>A new bid has been received</h2>

<hr>

<p>
  Dear {{ $name }},<br><br>
  You have received a new bid on <b>{{ $itemName }}</b>. Here is the dealer information.<br><br>
  <b>Name</b>: {{ $dealer->name }}<br>
  <b>Email</b>: {{ $dealer->email }}<br>
  
  @if ($dealer->phone)
  <b>Phone</b>: {{ $dealer->phone }}<br>
  @endif

  <b>Address</b>: {{ $dealer->address ?? "" }}, {{ $dealer->city ?? "" }} {{ $dealer->state ?? "" }} {{ $dealer->zip_code ?? "" }}<br>

  @if ($price)
  <b>Price</b>: ${{ $price }}
  @endif
</p>
