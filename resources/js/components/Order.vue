<template>
    <div>
      <h2 class="text-2xl font-bold">Danh sách đơn hàng</h2>

      <table class="table-auto w-full border-collapse border border-gray-300 mt-4">
        <thead>
          <tr class="bg-gray-200">
            <th class="border px-4 py-2">ID</th>
            <th class="border px-4 py-2">Trạng thái</th>
            <th class="border px-4 py-2">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders" :key="order.id">
            <td class="border px-4 py-2">{{ order.id }}</td>
            <td class="border px-4 py-2">
              <span v-if="order.status === 0">🟡 Chờ xử lý</span>
              <span v-if="order.status === 5">✅ Đã thanh toán</span>
              <span v-if="order.status === 4">❌ Đã hủy</span>
            </td>
            <td class="border px-4 py-2">
              <button v-if="order.status === 0"
                      @click="confirmPayment(order.id)"
                      class="bg-green-500 text-white px-3 py-1 rounded">
                Thanh toán
              </button>
              <button v-if="order.status === 0"
                      @click="cancelOrder(order.id)"
                      class="bg-red-500 text-white px-3 py-1 ml-2 rounded">
                Hủy đơn
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <script>
  import axios from "axios";

  export default {
    data() {
      return {
        orders: []
      };
    },
    mounted() {
      this.fetchOrders();
    },
    methods: {
      async fetchOrders() {
        try {
          const response = await axios.get("http://127.0.0.1:8000/api/client/orders");
          this.orders = response.data.orders;
        } catch (error) {
          console.error("Lỗi khi lấy danh sách đơn hàng:", error);
        }
      },
      async confirmPayment(orderId) {
        try {
          const response = await axios.post("http://127.0.0.1:8000/api/client/orders/confirm-payment", {
            order_id: orderId
          });
          alert(response.data.message);
          this.fetchOrders(); // Refresh danh sách đơn hàng
        } catch (error) {
          console.error("Lỗi khi xác nhận thanh toán:", error);
          alert("Không thể thanh toán đơn hàng này!");
        }
      },
      async cancelOrder(orderId) {
        try {
          const response = await axios.post("http://127.0.0.1:8000/api/client/orders/cancel-order", {
            order_id: orderId
          });
          alert(response.data.message);
          this.fetchOrders(); // Refresh danh sách đơn hàng
        } catch (error) {
          console.error("Lỗi khi hủy đơn hàng:", error);
          alert("Không thể hủy đơn hàng này!");
        }
      }
    }
  };
  </script>
