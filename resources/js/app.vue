<template>
  <div id="app">
    <header>
      <h1 class="text-dark">Welcome to My Vue App</h1>
    </header>
    <nav>
            <router-link :to="{ name: 'home' }">Home</router-link> |
            <router-link :to="{ name: 'create' }" class="btn btn-primary">Add New</router-link>
        </nav>
    <main>
      <router-view />
   
    </main>
    <footer>
      <p>&copy; 2024 My Vue App</p>
    </footer>
  </div>
</template>

<script>
export default {
  name: "App",
};
</script>

<style>
#app {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
}

header {
  background-color: #4CAF50;
  color: white;
  padding: 1rem;
  text-align: center;
}

nav {
  background-color: #333;
  color: white;
  padding: 0.5rem;
  text-align: center;
}

nav a {
  color: white;
  margin: 0 1rem;
  text-decoration: none;
}

nav a:hover {
  text-decoration: underline;
}

main {
  padding: 2rem;
}

footer {
  background-color: #4CAF50;
  color: white;
  text-align: center;
  padding: 1rem;
  position: fixed;
  bottom: 0;
  width: 100%;
}
</style>
