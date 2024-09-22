<x-layout>

    <h1>購物網站頁面</h1>

    <a href="{{ route('checkout-page') }}">購物車</a>

    @php

        echo time(); // 這會顯示當前的時間戳

    @endphp


    <form action="{{ route('ecpay.query') }}" method="POST">
        @csrf
        <button>查詢訂單</button>
    </form>

</x-layout>
