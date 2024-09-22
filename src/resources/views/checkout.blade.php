<x-layout>

    <h1>綠界金流測試訂單</h1>

    <form action="{{ route('ecpay.checkout') }}" method="POST">
        @csrf
        <div>
            <label for="amount">金額:</label>
            <input type="number" name="amount" id="amount" value="100" required>
        </div>
        <div>
            <label for="item_name">商品名稱:</label>
            <input type="text" name="item_name" id="item_name" value="範例商品一批 100 TWD x 1" required>
        </div>
        <button type="submit">送出訂單</button>
    </form>


</x-layout>
