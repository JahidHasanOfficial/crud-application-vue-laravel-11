<template>
    <div>
      <h1>Create Product</h1>
      <router-view />
  
      <div class="container m-5">
        <form @submit.prevent="submitForm">

          <!-- Category Field -->
          <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select v-model="form.category_id" class="form-control" id="category_id" required>
              <option value="">Select Category</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
  
          <!-- Name Field -->
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input v-model="form.name" type="text" class="form-control" id="name" placeholder="Enter product name" required />
          </div>
  
          <!-- Description Field -->
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea v-model="form.description" class="form-control" id="description" rows="3" placeholder="Enter product description" required></textarea>
          </div>
  
          <!-- Image Field -->
          <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" accept="image/*" @change="handleImageChange" required />
          </div>
  
          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary">Submitsdfsdfsd</button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        form: {
          category_id: '',
          name: '',
          description: '',
          image: null
        },
        categories: []
      };
    },
    mounted() {
      this.fetchCategories();
    },
    methods: {
      fetchCategories() {

        axios.get('/category')
          .then(response => {
            console.log("Categories:", response.data.data);
            this.categories = response.data.data; 
          })
          .catch(error => {
            console.log("Error fetching categories:", error);
          });
      },
      handleImageChange(event) {

        this.form.image = event.target.files[0];
      },
      submitForm() {
        const formData = new FormData();
        formData.append('category_id', this.form.category_id);
        formData.append('name', this.form.name);
        formData.append('description', this.form.description);
        formData.append('image', this.form.image);

        axios.post('/product/update', formData)
          .then(response => {
            console.log('Product created successfully:', response.data);
            this.$router.push('/home'); 
          })
          .catch(error => {
            console.error('Error creating product:', error);
          });
      }
    }
  };
  </script>
  
  <style scoped>

  </style>
  