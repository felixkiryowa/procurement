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
            <div class="row">
              <div class="col-md-12">
                <h3 class="font-weight-bold">Welcome Aamir</h3>
                <h6 class="font-weight-normal">
                  All systems are running smoothly! You have
                  <span class="text-primary">3 unread alerts!</span>
                </h6>
              </div>
            </div>
            <br />
            <h4 class="font-weight-bold">Register For An Account</h4>
            <hr />
            <form-wizard
              @onComplete="submitRegistration"
              @onNextStep="nextStep"
              @onPreviousStep="previousStep"
            >
              <tab-content title="Choose Account Type" :selected="true">
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
                <div class="form-group">
                  <label for="account_type">Account Type</label>
                  <select
                    class="form-control"
                    v-model="formData.account_type"
                    :class="hasError('account_type') ? 'is-invalid' : ''"
                  >
                    <option value="">Choose Option</option>
                    <option value="Provider">Service Provider</option>
                    <option value="Company">Company</option>
                  </select>
                  <div v-if="hasError('account_type')" class="invalid-feedback">
                    <div class="error" v-if="!$v.formData.account_type.required">
                      Choose An Account Type
                    </div>
                  </div>
                </div>
              </tab-content>
              <tab-content title="About Your Account">
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
              <tab-content title="Confirm Contact">
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
import { FormWizard, TabContent, ValidationHelper } from "vue-step-wizard";

export default {
  mixins: [ValidationHelper],
  data() {
    return {
      formData: {
        account_type: "",
        fullName: "",
        companyName: "",
        referral: "",
      },
      validationRules: [
        { fullName: { required },account_type: { required } },
        { companyName: { required } },
        { referral: { required } },
      ],
    };
  },
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

  methods: {
    submitRegistration() {},

    nextStep() {
      console.log("Next Step");
    },

    previousStep() {
      console.log("Previous Step");
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
