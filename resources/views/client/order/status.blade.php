<style>
    .order-tracking {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        max-width: 900px;
        margin: 20px auto;
        position: relative;
    }

    .order-tracking::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 4px;
        background: #ddd;
        transform: translateY(-50%);
        z-index: 1;
    }

    .step {
        position: relative;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ddd; /* Mặc định xám */
        border: none;
        z-index: 2;
    }

    .step.active {
        background-color: #4CAF50; /* Xanh lá */
    }

    .step i {
        font-size: 24px;
    }

    .step-title {
        text-align: center;
        margin-top: 8px;
        font-size: 14px;
        color: #333;
    }
</style>

<div class="order-tracking">
    <div class="step {{ $order->status >= 1 ? 'active' : '' }}">
        <i>📄</i>
    </div>
    <div class="step {{ $order->status >= 2 ? 'active' : '' }}">
        <i>💰</i>
    </div>
    <div class="step {{ $order->status >= 3 ? 'active' : '' }}">
        <i>🚚</i>
    </div>
    <div class="step {{ $order->status >= 4 ? 'active' : '' }}">
        <i>📦</i>
    </div>
    <div class="step {{ $order->status >= 5 ? 'active' : '' }}">
        <i>⭐</i>
    </div>
</div>

<div style="display: flex; justify-content: space-between; max-width: 900px; margin: 0 auto;">
    <span class="step-title">Đơn Hàng Đã Đặt</span>
    <span class="step-title">Đã Thanh Toán</span>
    <span class="step-title">Giao Cho ĐVVC</span>
    <span class="step-title">Đang Giao</span>
    <span class="step-title">Đánh Giá</span>
</div>
