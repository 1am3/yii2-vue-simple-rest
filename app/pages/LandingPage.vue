<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <h2>Товары ({{products.length}})</h2>
        <table class="table table-bordered table-hover">
          <thead>
          <tr>
            <th class="text-center"></th>
            <th class="text-left">#</th>
            <th class="text-center">Наименование</th>
            <th class="text-center">Цена</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(product, index) in products">
            <td><input type="checkbox" name="id_product[]" :value="product.id" v-model="purchase_products"></td>
            <td>{{product.id}}</td>
            <td>{{product.name}}</td>
            <td>{{product.price}}</td>
          </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-12">
        <button id="generate" class="btn btn-default" v-on:click="generateProducts">Сгенрировать товары</button>
        <button id="create_purchase" class="btn btn-info" v-on:click="createPurchase">Создать заказ</button>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>Сумма оплаты</label>
          <input name="sum" class="form-control" :value="sum" v-on:input="sum = $event.target.value"/>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>Номер заказа</label>
          <input name="sum" class="form-control" :value="id_purchase" placeholder="Номер заказа" v-on:input="id_purchase = $event.target.value"/>
        </div>
      </div>
      <div class="col-md-12">
          <button id="pay_purchase" class="btn btn-success" v-on:click="payPurchase">Оплатить заказ</button>
      </div>
      <div class="col-md-12">
        <h2>Товары в заказ</h2>
        <div id="purchase">
          {{purchase_products}}
        </div>
      </div>
    </div>


  </div>
</template>
<script>
export default {
  data() {
    return {
      'products' : '',
      'purchase_products' : [],
      'id_purchase': 0,
      'sum': 0,
    }
  },
  methods: {
    getProducts() {
      const path = '/product/';
      axios.get(path)
          .then((res) => {
            if(res.data.items)
            {
              this.products = res.data.items;
            }
          })
          .catch((error) => {
          });
    },
    generateProducts() {
      const path = '/product/generate';
      axios.get(path)
          .then((res) => {
            this.getProducts();
          })
          .catch((error) => {
          });

    },
    createPurchase() {
      const path = '/purchase/create';
      const data = new FormData();

      this.purchase_products.forEach((item) => {
        data.append('id_product[]', item);
      });
      axios.post(path, data)
          .then((res) => {
            let data = res.data;
            if(data.id_purchase)
            {
              this.id_purchase = data.id_purchase;
              this.sum = data.sum;
            }
            if(data.error)
            {
              alert(data.reason);
            }

          })
          .catch((error) => {
          });

    },
    payPurchase() {
      const path = '/purchase/pay/';
      let data = new FormData();
      data.append('id_purchase', this.id_purchase);
      data.append('sum', this.sum);
      axios.post(path,data)
          .then((res) => {
            let data = res.data;
            if(data.error)
            {
              alert(data.reason);
            }
            if(data.status)
            {
              alert(data.status);
              this.id_purchase = 0;
              this.sum = 0;
              this.purchase_products = [];
            }
          })
          .catch((error) => {
          });
    }
  },
  created() {
    this.getProducts();
  }
}
</script>
