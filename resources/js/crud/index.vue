<template>
    <div id="app">
      <main>
        <router-view />
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Description</th>
              <th scope="col">Image</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(product, index) in products" :key="product.id">
              <th scope="row">{{ index + 1 }}</th>
              <td>{{ product.name }}</td>
              <td>{{ product.description }}</td>
              <td><img :src="product.image" alt="Product Image" style="width: 50px; height: 50px;"></td>
              <td>
                <router-link :to="{ name: 'edit', params: { id: product.id } }" class="btn btn-primary">Edit</router-link>
                <button @click="deleteProduct(product.id)" class="btn btn-danger" style="margin-left: 10px;">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </main>
      <footer>
        <p>&copy; 2024 My Vue App</p>
      </footer>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  export default {
    data() {
      return {
        products: []
      };
    },
    mounted() {
      this.productList();
    },
    methods: {
      productList() {
        axios
          .get("/product")
          .then((success) => {
            console.log("API Response:", success.data);
            this.products = success.data.data;
          })
          .catch((errors) => {
            console.log("Error fetching products:", errors.response || errors);
          });
      },
      deleteProduct(productId) {
  // Show a confirmation dialog before deleting
  if (confirm("Are you sure you want to delete this product?")) {
    axios
      .post('/product/delete', { id: productId })  // Sending the id in the body
      .then((success) => {
        console.log("Product deleted successfully:", success.data);
        // Remove the deleted product from the products array
        this.products = this.products.filter(product => product.id !== productId);
      })
      .catch((error) => {
        console.log("Error deleting product:", error.response || error);
      });
  }
}



    }
  };
  </script>
  