<template>
  <div>
    <footer class="footer">
      <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span
          class="text-muted text-center text-sm-left d-block d-sm-inline-block"
          >Copyright © {{ year }}. Public Procurement. All rights reserved.</span
        >
      </div>
    </footer>
  </div>
</template>
<script>
export default {
  name: "FooterComponent",
  props: {
    year: String
  },

  data() {
    return {
      isRegistration: false,
    };
  },

  methods: {},

  created() {},

  components: {
  },
};
</script>
