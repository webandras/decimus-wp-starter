<template>
  <ul class="nav">
    <li class="nav-item">
      <router-link
        to="/global"
        class="nav-link"
        active-class="active"
        role="link"
        >Global
      </router-link>
      <!-- .nav-item -->
    </li>
    <li class="nav-item">
      <router-link
        to="/contact"
        class="nav-link"
        active-class="active"
        role="link"
        >Contact Information
      </router-link>
      <!-- .nav-item -->
    </li>
    <li class="nav-item">
      <router-link
        to="/header"
        class="nav-link"
        active-class="active"
        role="link"
        >Header
      </router-link>
      <!-- .nav-item -->
    </li>
    <li class="nav-item">
      <router-link
        to="/admin"
        class="nav-link"
        active-class="active"
        role="link"
        >Admin Settings
      </router-link>
      <!-- .nav-item -->
    </li>
    <li class="nav-item">
      <router-link
        to="/woocommerce"
        class="nav-link"
        active-class="active"
        role="link"
        >WooCommerce
      </router-link>
      <!-- .nav-item -->
    </li>
    <li class="nav-item">
      <router-link
        to="/about"
        class="nav-link"
        active-class="active"
        role="link"
        >About
      </router-link>
      <!-- .nav-item -->
    </li>
  </ul>
</template>

<script>
export default {};
</script>

<style scoped>
ul.nav li {
  margin-bottom: 30px;
}
ul.nav li > a {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
    Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
  font-style: normal;
  font-weight: normal;
  font-size: 14px;
  line-height: 16px;
  letter-spacing: 0.02em;
  color: #3f57b9;
  text-decoration: none;
}
ul.nav li > a:hover, ul.nav li > a:focus {
  transition: all 0.4s;
  text-decoration: underline;
  color: #2e4397;
}
ul.nav li > a.active {
  font-weight: bold;
  text-decoration: underline;
}

</style>
