<template>
  <div>
    <NavBarComponent :appName="app" :user="user" />
    <br />
    <br />
    <br />
    <div class="main-panel-custom">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-12">
            <form-wizard>
              <tab-content title="About You" :selected="true">
                <div class="form-group">
                  <label for="fullName">Full Name</label>
                  <input
                    type="text"
                    class="form-control"
                    :class="hasError('fullName') ? 'is-invalid' : ''"
                    placeholder="Enter your name"
                    v-model="formData.fullName"
                  />
                  <div v-if="hasError('fullName')" class="invalid-feedback">
                    <div class="error" v-if="!$v.formData.fullName.required">
                      Please provide a valid name.
                    </div>
                  </div>
                </div>
              </tab-content>
              <tab-content title="About your Company">
                <div class="form-group">
                  <label for="companyName">Your Company Name</label>
                  <input
                    type="text"
                    class="form-control"
                    :class="hasError('companyName') ? 'is-invalid' : ''"
                    placeholder="Enter your Company / Organization name"
                    v-model="formData.companyName"
                  />
                  <div v-if="hasError('companyName')" class="invalid-feedback">
                    <div class="error" v-if="!$v.formData.companyName.required">
                      Please provide a valid company name.
                    </div>
                  </div>
                </div>
              </tab-content>
              <tab-content title="Finishing Up">
                <div class="form-group">
                  <label for="referral">From Where did you hear about us</label>
                  <select
                    class="form-control"
                    :class="hasError('referral') ? 'is-invalid' : ''"
                    v-model="formData.referral"
                  >
                    <option>Newspaper</option>
                    <option>Online Ad</option>
                    <option>Friend</option>
                    <option>Other</option>
                  </select>
                  <div v-if="hasError('referral')" class="invalid-feedback">
                    <div class="error" v-if="!$v.formData.referral.required">
                      Please where you heard us from.
                    </div>
                  </div>
                </div>
              </tab-content>
            </form-wizard>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NavBarComponent from "../NavBar/NavBarComponent.vue";
import Loading from "vue-loading-overlay";
import { mapActions, mapState, mapGetters } from "vuex";
import { required } from "vuelidate/lib/validators";
import { email } from "vuelidate/lib/validators";
import { numeric } from "vuelidate/lib/validators";
//local registration
import { FormWizard, TabContent, ValidationHelper } from "vue-step-wizard";
import "vue-step-wizard/dist/vue-step-wizard.css";

export default {
  data() {
    return {
      formData: { fullName: "", companyName: "", referral: "" },
      validationRules: [
        { fullName: { required } },
        { companyName: { required } },
        { referral: { required } },
      ],
    };
  },
  mixins: [ValidationHelper],
  mounted() {},

  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    app() {
      return this.$page.props.appName;
    },
    checkIfUserIsIdle() {
      return this.isAppIdle ? true : false;
    },
  },

  components: {
    NavBarComponent,
    Loading,
    FormWizard,
    TabContent,
  },
};
</script>
