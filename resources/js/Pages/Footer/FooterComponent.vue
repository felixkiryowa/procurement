<template>
  <div>
    <footer class="footer">
            <div
              class="d-sm-flex justify-content-center justify-content-sm-between"
            >
              <span
                class="
                  text-muted text-center text-sm-left
                  d-block d-sm-inline-block
                "
                >Copyright © 2022. QED Procurement.
                <a href="#" target="_blank"
                  >Terms & Conditions.</a
                >
                 All rights reserved.</span
              >

            </div>

          </footer>
  </div>
</template>
<script>
import Helpers from "../../Helpers/Helpers";
import { Link } from "@inertiajs/inertia-vue";
export default {
  name: "NavBarComponent",
  props: {
    appName: String,
    user: Object,
  },

  data() {
    return {
      isRegistration: false,
    };
  },

  methods: {



  },

  created() {
  },

  components: {
    Link,
  },
};
</script>
