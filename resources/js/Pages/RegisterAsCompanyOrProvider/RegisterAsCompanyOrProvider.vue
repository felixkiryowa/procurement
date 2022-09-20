<template>
  <div>
    <NavBarComponent :appName="app" :user="user" />
    <br />
    <br />
    <br />
    <div class="main-panel-custom">
      <div class="content-wrapper">
        <div class="vld-parent">
          <loading
            :active.sync="isLoading"
            :can-cancel="false"
            color="#074578"
            loader="spinner"
            :opacity="0.5"
            :is-full-page="fullPage"
          />
        </div>
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
                <div class="custom-border1">
                  <div class="form-group">
                    <label for="account_type">Account Type</label>
                    <select
                      class="form-control"
                      v-model="formData.account_type"
                      :class="hasError('account_type') ? 'is-invalid' : ''"
                    >
                      <option value="" disabled>Choose Option</option>
                      <option value="1">Register As Service Provider</option>
                      <option value="2">Register As A Company</option>
                    </select>
                    <div
                      v-if="hasError('account_type')"
                      class="invalid-feedback"
                    >
                      <div
                        class="error"
                        v-if="!$v.formData.account_type.required"
                      >
                        Choose An Account Type
                      </div>
                    </div>
                  </div>
                </div>
              </tab-content>
              <tab-content title="About Your Account">
                <div class="row">
                  <div class="col-md-5">
                    <div class="custom-border">
                      <fieldset>
                        <legend>About Your Organisation</legend>
                        <div class="form-group">
                          <label for="organisationName"
                            >Organization Name</label
                          >
                          <input
                            type="text"
                            class="form-control"
                            :class="
                              hasError('organisationName') ? 'is-invalid' : ''
                            "
                            placeholder="Enter Organisation Name"
                            v-model="formData.organisationName"
                          />
                          <div
                            v-if="hasError('organisationName')"
                            class="invalid-feedback"
                          >
                            <div
                              class="error"
                              v-if="!$v.formData.organisationName.required"
                            >
                              Organisation Name is required.
                            </div>
                          </div>
                        </div>
                        <p>Select Procurement Category</p>
                        <div class="form-check form-check-inline">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            v-model="formData.procurementCategory"
                            id="Goods"
                            value="Goods"
                          />
                          <label class="form-check-label" for="Goods"
                            >Goods</label
                          >
                        </div>
                        <div class="form-check form-check-inline">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            v-model="formData.procurementCategory"
                            id="Services"
                            value="Services"
                          />
                          <label class="form-check-label" for="Services"
                            >Services</label
                          >
                        </div>
                        <div class="form-check form-check-inline">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            v-model="formData.procurementCategory"
                            id="Works"
                            value="Works"
                          />
                          <label class="form-check-label" for="Works"
                            >Works</label
                          >
                        </div>
                        <div
                          v-if="hasError('procurementCategory')"
                          class="invalid-feedback"
                        >
                          <div
                            class="error"
                            v-if="
                              !$v.formData.procurementCategory
                                .procurementCategoryMustBeSelected
                            "
                          >
                            Choose one of the procurement category
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="briefDescription"
                            >Brief Description about organisations</label
                          >
                          <textarea
                            class="form-control"
                            :class="
                              hasError('briefDescription') ? 'is-invalid' : ''
                            "
                            v-model="formData.briefDescription"
                            rows="3"
                          ></textarea>
                          <div
                            v-if="hasError('briefDescription')"
                            class="invalid-feedback"
                          >
                            <div
                              class="error"
                              v-if="!$v.formData.briefDescription.required"
                            >
                              Organisation Description field is required
                            </div>
                          </div>
                        </div>
                      </fieldset>
                      <div class="row">
                        <div class="col-md-12">
                          <fieldset>
                            <legend>Your Organization Registration</legend>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="taxId">Tax ID</label>
                                  <input
                                    type="text"
                                    class="form-control"
                                    :class="
                                      hasError('taxId') ? 'is-invalid' : ''
                                    "
                                    placeholder="Enter Tax Id"
                                    v-model="formData.taxId"
                                  />
                                  <div
                                    v-if="hasError('taxId')"
                                    class="invalid-feedback"
                                  >
                                    <div
                                      class="error"
                                      v-if="!$v.formData.taxId.required"
                                    >
                                      Tax ID is required
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="registrationNumber"
                                    >Registration Number</label
                                  >
                                  <input
                                    type="text"
                                    class="form-control"
                                    :class="
                                      hasError('registrationNumber')
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    placeholder="Enter Registration Number"
                                    v-model="formData.registrationNumber"
                                  />
                                  <div
                                    v-if="hasError('registrationNumber')"
                                    class="invalid-feedback"
                                  >
                                    <div
                                      class="error"
                                      v-if="
                                        !$v.formData.registrationNumber.required
                                      "
                                    >
                                      Registration number is required
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="country">Country</label>
                              <select
                                class="form-control"
                                :class="hasError('country') ? 'is-invalid' : ''"
                                v-model="formData.country"
                              >
                                <option value="">Choose a country</option>
                                <option
                                  v-for="country in allCountries"
                                  :key="country.name"
                                  :value="country.name"
                                >
                                  {{ country.name }}
                                </option>
                              </select>
                              <div
                                v-if="hasError('country')"
                                class="invalid-feedback"
                              >
                                <div
                                  class="error"
                                  v-if="!$v.formData.country.required"
                                >
                                  Country field is required
                                </div>
                              </div>
                            </div>
                          </fieldset>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <div class="custom-border">
                      <div class="row">
                        <div class="col-md-12">
                          <fieldset>
                            <legend>Your Account Details</legend>
                            <div class="form-group">
                              <label for="userName">Username</label>
                              <input
                                type="text"
                                class="form-control"
                                :class="
                                  hasError('userName') ? 'is-invalid' : ''
                                "
                                placeholder="Enter Username(Minimum with 6 characters)"
                                v-model="formData.userName"
                              />
                              <div
                                v-if="hasError('userName')"
                                class="invalid-feedback"
                              >
                                <div
                                  class="error"
                                  v-if="!$v.formData.userName.required"
                                >
                                  Username is required
                                </div>
                                <div
                                  class="error"
                                  v-if="!$v.formData.userName.minLength"
                                >
                                  Username should not be less than 6 characters
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="password">Password</label>
                                  <input
                                    type="password"
                                    class="form-control"
                                    :class="
                                      hasError('password') ? 'is-invalid' : ''
                                    "
                                    placeholder="Enter Pawword(Minimum with 8 characters)"
                                    v-model="formData.password"
                                  />
                                  <div
                                    v-if="hasError('password')"
                                    class="invalid-feedback"
                                  >
                                    <div
                                      class="error"
                                      v-if="!$v.formData.password.required"
                                    >
                                      Password is required
                                    </div>
                                    <div
                                      class="error"
                                      v-if="!$v.formData.password.minLength"
                                    >
                                      Password should not be less than 8
                                      characters
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="confirmPassword"
                                    >Confirm Password</label
                                  >
                                  <input
                                    type="password"
                                    class="form-control"
                                    :class="
                                      hasError('confirmPassword')
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    placeholder="Enter Pawword(Minimum with 8 characters)"
                                    v-model="formData.confirmPassword"
                                  />
                                  <div
                                    v-if="hasError('confirmPassword')"
                                    class="invalid-feedback"
                                  >
                                    <div
                                      class="error"
                                      v-if="
                                        !$v.formData.confirmPassword.required
                                      "
                                    >
                                      Confirm Password field is required
                                    </div>
                                    <div
                                      class="error"
                                      v-if="
                                        !$v.formData.confirmPassword
                                          .sameAsPassword
                                      "
                                    >
                                      Passwords Do not Match
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="email">Email</label>
                              <input
                                type="email"
                                class="form-control"
                                :class="hasError('email') ? 'is-invalid' : ''"
                                placeholder="Enter Email Address"
                                v-model="formData.email"
                              />
                              <div
                                v-if="hasError('email')"
                                class="invalid-feedback"
                              >
                                <div
                                  class="error"
                                  v-if="!$v.formData.email.required"
                                >
                                  Email Address field is required
                                </div>
                                <div
                                  class="error"
                                  v-if="!$v.formData.email.email"
                                >
                                  Invalid Email Address
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="email"
                                    >Company Phone Number</label
                                  >
                                  <input
                                    type="number"
                                    class="form-control"
                                    :class="
                                      hasError('companyPhoneNumber')
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    placeholder="Enter Phone Number"
                                    v-model="formData.companyPhoneNumber"
                                  />
                                  <div
                                    v-if="hasError('companyPhoneNumber')"
                                    class="invalid-feedback"
                                  >
                                    <div
                                      class="error"
                                      v-if="
                                        !$v.formData.companyPhoneNumber.required
                                      "
                                    >
                                      Phone Number field is required
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="alternativeCompanyPhoneNumber"
                                    >Alternative Company Phone
                                    Number(Optional)</label
                                  >
                                  <input
                                    type="number"
                                    class="form-control"
                                    :class="
                                      hasError('alternativeCompanyPhoneNumber')
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    placeholder="Enter Alternative Phone Number"
                                    v-model="
                                      formData.alternativeCompanyPhoneNumber
                                    "
                                  />
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="secretQuestion"
                                    >Secret Question</label
                                  >
                                  <select
                                    class="form-control"
                                    :class="
                                      hasError('secretQuestion')
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    v-model="formData.secretQuestion"
                                  >
                                    <option disabled value="">
                                      Choose an option
                                    </option>
                                    <option
                                      v-for="question in secret_questions"
                                      :key="question.id"
                                      :value="question.name"
                                    >
                                      {{ question.name }}
                                    </option>
                                  </select>
                                  <div
                                    v-if="hasError('secretQuestion')"
                                    class="invalid-feedback"
                                  >
                                    <div
                                      class="error"
                                      v-if="
                                        !$v.formData.secretQuestion.required
                                      "
                                    >
                                      Secret Question field is required
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="secretAnswer"
                                    >Secret Answer</label
                                  >
                                  <input
                                    type="text"
                                    class="form-control"
                                    :class="
                                      hasError('secretAnswer')
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    placeholder="Enter Your Answer"
                                    v-model="formData.secretAnswer"
                                  />
                                  <div
                                    v-if="hasError('secretAnswer')"
                                    class="invalid-feedback"
                                  >
                                    <div
                                      class="error"
                                      v-if="!$v.formData.secretAnswer.required"
                                    >
                                      Secret Answer field is required
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <hr />
                          </fieldset>

                          <fieldset>
                            <legend>Verify that your not a reboot</legend>
                            <div class="row">
                              <div class="col-md-6">
                                Answer this problem to prove you are human:
                                <b>{{ challengeNumber1.toString() }}</b>
                                <b>+</b>
                                <b>{{ challengeNumber2.toString() }}</b>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="challengeAnswer"
                                    >Challenge Answer</label
                                  >
                                  <input
                                    type="number"
                                    class="form-control"
                                    :class="
                                      hasError('challengeAnswer')
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    placeholder="Enter Challenge Answer"
                                    v-model="formData.challengeAnswer"
                                  />
                                  <div
                                    v-if="hasError('challengeAnswer')"
                                    class="invalid-feedback"
                                  >
                                    <div
                                      class="error"
                                      v-if="
                                        !$v.formData.challengeAnswer.required
                                      "
                                    >
                                      Challenge Answer field is required
                                    </div>

                                    <div
                                      class="error"
                                      v-if="
                                        !$v.formData.challengeAnswer
                                          .sameAsPassword
                                      "
                                    >
                                      Challenge Answer is wrong
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </fieldset>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </tab-content>
              <tab-content title="Confirm Contact">
                <div class="custom-border2">
                  <fieldset>
                    <legend>Confirm Your Email Address</legend>
                    <div class="form-group">
                      <label for="codeSentToEmail">Enter Code</label>
                      <input
                        type="number"
                        class="form-control"
                        :class="hasError('codeSentToEmail') ? 'is-invalid' : ''"
                        placeholder="Enter Code Sent To Your Email"
                        v-model="formData.codeSentToEmail"
                      />
                      <div
                        v-if="hasError('codeSentToEmail')"
                        class="invalid-feedback"
                      >
                        <div
                          class="error"
                          v-if="!$v.formData.codeSentToEmail.required"
                        >
                          Code Sent To Email is required
                        </div>

                        <div
                          class="error"
                          v-if="!$v.formData.codeSentToEmail.sameAsPassword"
                        >
                          Secret Code does not match one send to your email
                        </div>
                      </div>
                    </div>
                  </fieldset>
                  <fieldset>
                    <legend>Your Contact Address:</legend>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="firstName">Enter First Name</label>
                          <input
                            type="text"
                            class="form-control"
                            :class="hasError('firstName') ? 'is-invalid' : ''"
                            placeholder="Enter First Name"
                            v-model="formData.firstName"
                          />
                          <div
                            v-if="hasError('firstName')"
                            class="invalid-feedback"
                          >
                            <div
                              class="error"
                              v-if="!$v.formData.firstName.required"
                            >
                              First Name field is required
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="lastName">Enter Last Name</label>
                          <input
                            type="text"
                            class="form-control"
                            :class="hasError('lastName') ? 'is-invalid' : ''"
                            placeholder="Enter Last Name"
                            v-model="formData.lastName"
                          />
                          <div
                            v-if="hasError('lastName')"
                            class="invalid-feedback"
                          >
                            <div
                              class="error"
                              v-if="!$v.formData.lastName.required"
                            >
                              Last Name field is required
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="address">Enter Address</label>
                          <input
                            type="text"
                            class="form-control"
                            :class="hasError('address') ? 'is-invalid' : ''"
                            placeholder="Enter Your Address"
                            v-model="formData.address"
                          />
                          <div
                            v-if="hasError('address')"
                            class="invalid-feedback"
                          >
                            <div
                              class="error"
                              v-if="!$v.formData.address.required"
                            >
                              Address field is required
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="city">City</label>
                          <input
                            type="text"
                            class="form-control"
                            :class="hasError('city') ? 'is-invalid' : ''"
                            placeholder="Enter Code Sent To Your Email"
                            v-model="formData.city"
                          />
                          <div v-if="hasError('city')" class="invalid-feedback">
                            <div
                              class="error"
                              v-if="!$v.formData.city.required"
                            >
                              City field is required
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="region">Region</label>
                          <input
                            type="text"
                            class="form-control"
                            :class="hasError('region') ? 'is-invalid' : ''"
                            placeholder="Enter Your Current Region"
                            v-model="formData.region"
                          />
                          <div
                            v-if="hasError('region')"
                            class="invalid-feedback"
                          >
                            <div
                              class="error"
                              v-if="!$v.formData.region.required"
                            >
                              Region field is required
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="zipCode">Zip Code</label>
                          <input
                            type="text"
                            class="form-control"
                            :class="hasError('zipCode') ? 'is-invalid' : ''"
                            placeholder="Enter Zip Code"
                            v-model="formData.zipCode"
                          />
                          <div
                            v-if="hasError('zipCode')"
                            class="invalid-feedback"
                          >
                            <div
                              class="error"
                              v-if="!$v.formData.zipCode.required"
                            >
                              Zip Code field is required
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="originCountry">Country</label>
                      <select
                        class="form-control"
                        :class="hasError('originCountry') ? 'is-invalid' : ''"
                        v-model="formData.originCountry"
                      >
                        <option value="">Choose a country</option>
                        <option
                          v-for="country in allCountries"
                          :key="country.name"
                          :value="country.name"
                        >
                          {{ country.name }}
                        </option>
                      </select>
                      <div
                        v-if="hasError('originCountry')"
                        class="invalid-feedback"
                      >
                        <div
                          class="error"
                          v-if="!$v.formData.originCountry.required"
                        >
                          Country field is required
                        </div>
                      </div>
                    </div>
                  </fieldset>
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
import {
  required,
  email,
  numeric,
  minLength,
  sameAs,
} from "vuelidate/lib/validators";
import { FormWizard, TabContent, ValidationHelper } from "vue-step-wizard";
const procurementCategoryMustBeSelected = (value) => value.length > 0;

export default {
  props: {
    secret_questions: Array,
  },
  mixins: [ValidationHelper],
  data() {
    return {
      fullPage: true,
      challengeNumber1: "",
      challengeNumber2: "",
      formData: {
        account_type: "",
        organisationName: "",
        procurementCategory: [],
        briefDescription: "",
        userName: "",
        password: "",
        confirmPassword: "",
        email: "",
        companyPhoneNumber: "",
        alternativeCompanyPhoneNumber: "",
        secretQuestion: "",
        secretAnswer: "",
        challengeAnswer: "",
        country: "",
        registrationNumber: "",
        taxId: "",
        codeSentToEmail: "",
        firstName: "",
        lastName: "",
        address: "",
        city: "",
        originCountry: "",
        region: "",
        zipCode: "",
        additionalTotal: "",
        secret_code_sent: "",
        provider_or_company_id: "",
      },
      validationRules: [
        { account_type: { required } },
        {
          organisationName: { required },
          procurementCategory: {
            procurementCategoryMustBeSelected,
          },
          briefDescription: { required },
          taxId: { required },
          registrationNumber: { required },
          country: { required },
          userName: { required, minLength: minLength(6) },
          password: { required, minLength: minLength(8) },
          confirmPassword: {
            required,
            sameAsPassword: sameAs("password"),
          },
          email: { required, email },
          companyPhoneNumber: { required, numeric },
          secretQuestion: { required },
          secretAnswer: { required },
          challengeAnswer: {
            required,
            sameAsPassword: sameAs("additionalTotal"),
          },
        },
        {
          codeSentToEmail: {
            required,
            sameAsPassword: sameAs("secret_code_sent"),
            numeric,
          },
          firstName: { required },
          lastName: { required },
          address: { required },
          city: { required },
          region: { required },
          zipCode: { required },
          originCountry: { required },
        },
      ],
    };
  },
  mounted() {},
  computed: {
    ...mapState("registration", ["isLoading"]),
    ...mapGetters("registration", ["allCountries"]),

    user() {
      return this.$page.props.auth.user;
    },
    app() {
      return this.$page.props.appName;
    },
    checkIfUserIsIdle() {
      return this.isAppIdle ? true : false;
    },

    checkCurrentTab() {
      return this.storeState.currentTab;
    },
  },

  methods: {
    ...mapActions("registration", ["showLoader", "hideLoader"]),

    submitRegistration() {
      this.showLoader();

      const requestData = new FormData();
      requestData.append("account_type", this.formData.account_type);
      requestData.append("organisationName", this.formData.organisationName);
      const categories = this.formData.procurementCategory;
      requestData.append("procurementCategory", categories);
      requestData.append("briefDescription", this.formData.briefDescription);
      requestData.append("userName", this.formData.userName);
      requestData.append("password", this.formData.password);
      requestData.append("email", this.formData.email);
      requestData.append(
        "companyPhoneNumber",
        this.formData.companyPhoneNumber
      );
      requestData.append(
        "alternativeCompanyPhoneNumber",
        this.formData.alternativeCompanyPhoneNumber
      );
      requestData.append("secretQuestion", this.formData.secretQuestion);
      requestData.append("secretAnswer", this.formData.secretAnswer);
      requestData.append("challengeAnswer", this.formData.challengeAnswer);
      requestData.append("country", this.formData.country);
      requestData.append(
        "registrationNumber",
        this.formData.registrationNumber
      );
      requestData.append("taxId", this.formData.taxId);
      requestData.append("codeSentToEmail", this.formData.codeSentToEmail);
      requestData.append("firstName", this.formData.firstName);
      requestData.append("lastName", this.formData.lastName);
      requestData.append("address", this.formData.address);
      requestData.append("city", this.formData.city);
      requestData.append("originCountry", this.formData.originCountry);
      requestData.append("region", this.formData.region);
      requestData.append("zipCode", this.formData.zipCode);
      requestData.append("additionalTotal", this.formData.additionalTotal);
      requestData.append("secret_code_sent", this.formData.secret_code_sent);
      requestData.append(
        "provider_or_company_id",
        this.formData.provider_or_company_id
      );

      axios
        .post("/create/provider/or/company", requestData, {
          headers: {
            "X-Frame-Options": "sameorigin",
            "X-Content-Type-Options": "nosniff",
            "strict-transport-security": "max-age=31536000",
          },
        })
        .then((response) => {
          Swal.fire({
            icon: "success",
            title: "Response",
            text: response.data.message,
          });
          this.hideLoader();
          this.deleteFormDataKeys();
          window.location.href = "/";
        })
        .catch((error) => {
          this.hideLoader();
        });
    },

    deleteFormDataKeys() {
      this.formData.account_type = "";
      this.formData.organisationName = "";
      this.formData.procurementCategory = [];
      this.formData.briefDescription = "";
      this.formData.userName = "";
      this.formData.password = "";
      this.formData.confirmPassword = "";
      this.formData.email = "";
      this.formData.companyPhoneNumber = "";
      this.formData.alternativeCompanyPhoneNumber = "";
      this.formData.secretQuestion = "";
      this.formData.secretAnswer = "";
      this.formData.challengeAnswer = "";
      this.formData.country = "";
      this.formData.registrationNumber = "";
      this.formData.taxId = "";
      this.formData.codeSentToEmail = "";
      this.formData.firstName = "";
      this.formData.lastName = "";
      this.formData.address = "";
      this.formData.city = "";
      this.formData.originCountry = "";
      this.formData.region = "";
      this.formData.zipCode = "";
      this.formData.additionalTotal = "";
      this.formData.secret_code_sent = "";
      this.formData.provider_or_company_id = "";
    },

    nextStep() {
      if (this.checkCurrentTab === 1) {
        this.showLoader();

        const requestData = new FormData();
        requestData.append("email", this.formData.email);
        axios
          .post("/send/secret/code", requestData, {
            headers: {
              "X-Frame-Options": "sameorigin",
              "X-Content-Type-Options": "nosniff",
              "strict-transport-security": "max-age=31536000",
            },
          })
          .then((response) => {
            const sent_code = response.data.secret_code_sent;
            this.formData.secret_code_sent = sent_code.toString();
            this.formData.provider_or_company_id =
              response.data.provider_or_company_id;
            Swal.fire({
              icon: "success",
              title: "Sending Email Response",
              text: response.data.message,
            });
            this.hideLoader();
          })
          .catch((error) => {
            this.hideLoader();
          });
      }
    },

    previousStep() {
      // console.log("Previous Step");
    },
    generateNumbers() {
      this.challengeNumber1 = Math.floor(Math.random() * 11);
      this.challengeNumber2 = Math.floor(Math.random() * 11);

      const result = this.challengeNumber1 + this.challengeNumber2;

      this.formData.additionalTotal = result.toString();
    },
  },

  created() {
    this.generateNumbers();
    setTimeout(() => this.hideLoader(), 2000);
  },

  components: {
    NavBarComponent,
    Loading,
    FormWizard,
    TabContent,
  },
};
</script>
