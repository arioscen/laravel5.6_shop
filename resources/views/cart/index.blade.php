@extends('layouts.base')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"> </script>
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Cart</div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th scope="col">Item ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Number</th>
                            <th scope="col">Subtotal</th>
                            <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>{{ $item->name }}</td>
                                <td>${{ $item->price }}</td>
                                <td><input id="{{ $item->id }}" type="number" class="form-control-sm" value="{{ $item->pivot->number }}" min="1"</td>
                                <td>${{ $item->price*$item->pivot->number }}</td>
                                <form class="form-inline" action="{{ url('cart/delete/'.$item->id) }}" method="POST">
                                    {!! csrf_field() !!}
                                    <td><button type="submit" class="btn btn-secondary">Cancel</button></td>
                                </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-end">
                    <div class="col-2">
                        <h1>Total ${{ $total }}</h1>
                    </div> 
                    <div class="col-2">
                        <a class="btn btn-warning" href="#" role="button">Proceed to checkout</a>
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("input[type='number']").change(function() {
        $.ajax({
            url: 'cart/update/'+this.id,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                "number": this.value,
            },
            error: function() {
                alert('error');
            },
            success: function(response) {
                location.reload(true);
            }
        });
    });    
</script>
@endsection