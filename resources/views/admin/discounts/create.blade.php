@extends('layouts.admin')

@section('title', 'Thêm mã giảm giá')
@section('header', 'Thêm mã giảm giá')

@section('content')
    <form method="POST" action="{{ route('admin.discounts.store') }}" class="space-y-6">
        @csrf
        @include('admin.discounts._form', ['discount' => $discount])
    </form>
@endsection
