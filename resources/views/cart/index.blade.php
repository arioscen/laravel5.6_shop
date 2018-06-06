@extends('layouts.base')

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
                            <th scope="col">#</th>
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
                                <td>{{ $item->pivot->number }}</td>
                                <td>${{ $item->price*$item->pivot->number }}</td>
                                <td><a class="btn btn-primary" href="#" role="button">Link</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-end">
                    <div class="col-2">
                        <h1>Total ${{ $total }}</h1>
                    </div> 
                    <div class="col-1"></div>      
                </div>
            </div>
        </div>
    </div>
</div>
@endsection