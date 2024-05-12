@extends('layouts.app')

@section('content')

<h2 class="pt-4">Product List</h2>
<a href="{{ route('sync.products')}}" class="btn btn-primary mb-4">Sync First 15 Products</a>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Image</th>
            <th scope="col">Price</th>
            <th scope="col">Description</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($products as $product)    
            <tr>
                <th scope="row">{{ $product->name }}</th>
                <td>
                    @if($product->image_filename)
                        <img class="w-50" src="{{ $product->productImage() }}" alt="Product Image">
                    @else
                        <p>Image Syncing..</p>
                    @endif
                </td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>{!! $product->description !!}</td>
            </tr>
        @empty
            <tr>
                <td colspan=4 class="text-center">No synced products</td>
            </tr>
        @endforelse
        
    </tbody>
</table>

{{ $products->links() }}

@endsection