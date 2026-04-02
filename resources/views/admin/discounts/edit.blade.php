@extends('layouts.admin')

@section('title', 'Sửa mã giảm giá')
@section('header', 'Sửa mã giảm giá')

@section('content')
    <form method="POST" action="{{ route('admin.discounts.update', $discount) }}" class="space-y-6">
        @csrf
        @method('PUT')
        @include('admin.discounts._form', ['discount' => $discount])
    </form>
@endsection
