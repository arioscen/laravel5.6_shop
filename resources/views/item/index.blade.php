@extends('layouts.base')

@section('content')
<div class="row">
    @foreach($items as $item)
        <div class="col-2 mb-3">
            <div class="card">
                <div class="card-header">{{ $item->name }}</div>
                <div class="card-body">
                    <div class="row mb-2">
                        <span>Price:</span>
                        <span>{{ $item->price }}</span>
                    </div>
                    <div class="row">
                        <form class="form-inline" action="{{ url('cart') }}" method="POST">
                            {!! csrf_field() !!}
                            <div class="form-group mr-2">
                                <input hidden name="item_id" value="{{ $item->id }}">
                                <input name="number" type="number" class="form-control-sm" value="1" min="1" max="100">
                            </div>
                            <button type="submit" class="btn btn-secondary btn-sm">Add to Cart</button>
                        </form> 
                    </div>               
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
