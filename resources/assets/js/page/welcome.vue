<template>
<div class="jumbotron vertical-center">
  <h1>Welcome, {{name}}!</h1>

  <p class="lead">You have successfully logged in Admin.</p>

  <small><i class="fa fa-warning"></i> We log every actions and operations, please use with care.</small>
</div>
</template>

<script>
export default {
  data() {
    return {
      name: ''
    };
  },
  created() {
  }
};
</script>
