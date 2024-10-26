@extends('laundry.auth.layouts.app')
@section('title', 'Laundry')

@section('content')
    <section>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-10 text-center mx-auto">
                    <img src="{{ asset('assets/images/element/error.svg') }}" alt="image" class="h-lg-500px mb-4">
                    <h1 class="display-1 text-dark mb-0">404</h1>
                    <h2>Oh tidak, ada yang tidak beres!</h2>
                    <p class="mb-4">Entah ada yang salah atau halaman ini sudah tidak ada lagi.</p>
                    <a href="{{ url('/') }}" class="btn btn-dark rounded-0 mb-0">KEMBALI</a>
                </div>
            </div>
        </div>
    </section>
@endsection
