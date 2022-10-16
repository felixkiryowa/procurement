<template>
  <div class="container-scroller">
    <UserLoggedOnNavBarComponent :app="app" :user="user" />
    <div class="container-fluid page-body-wrapper">
      <SideBarComponent :user="user" />
      <!-- partial -->
      <div class="main-panel">
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
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">
                    Welcome {{ user.firstName }} {{ user.lastName }}
                  </h3>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <!-- <div class="card-header">
                      
                    </div> -->

                    <div class="card-body">
                      <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a
                            class="nav-link active"
                            id="home-tab"
                            data-toggle="tab"
                            href="#home"
                            role="tab"
                            aria-controls="home"
                            aria-selected="true"
                            >Edit Profile</a
                          >
                        </li>
                        <li class="nav-item">
                          <a
                            class="nav-link"
                            id="profile-tab"
                            data-toggle="tab"
                            href="#profile"
                            role="tab"
                            aria-controls="profile"
                            aria-selected="false"
                            >Procurement Plan Details Approvers</a
                          >
                        </li>
                        <li class="nav-item">
                          <a
                            class="nav-link"
                            id="contact-tab"
                            data-toggle="tab"
                            href="#contact"
                            role="tab"
                            aria-controls="contact"
                            aria-selected="false"
                            >Contact</a
                          >
                        </li>
                      </ul>
                      <div class="tab-content" id="myTabContent">
                        <div
                          class="tab-pane fade show active"
                          id="home"
                          role="tabpanel"
                          aria-labelledby="home-tab"
                        >
                          <form @submit.prevent="submitRegistration">
                            <h4>Edit {{ form.organisationName }} Profile</h4>
                            <hr />
                            <div class="row">
                              <div class="col-md-5">
                                <div class="custom-border-profile">
                                  <fieldset>
                                    <legend>About Your Organisation</legend>
                                    <hr />
                                    <div class="form-group">
                                      <label for="organisationName"
                                        >Organization Name</label
                                      >
                                      <input
                                        type="text"
                                        class="form-control"
                                        :class="{
                                          'is-invalid':
                                            $v.form.organisationName.$error,
                                        }"
                                        placeholder="Enter Organisation Name"
                                        v-model="form.organisationName"
                                      />
                                      <div
                                        class="invalid-feedback"
                                        v-if="
                                          !$v.form.organisationName.required
                                        "
                                      >
                                        Organisation Name is required.
                                      </div>
                                    </div>
                                    <p>Select Procurement Category</p>
                                    <div class="form-check form-check-inline">
                                      <input
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="form.procurementCategory"
                                        id="Goods"
                                        value="Goods"
                                      />
                                      <label
                                        class="form-check-label"
                                        for="Goods"
                                        >Goods</label
                                      >
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="form.procurementCategory"
                                        id="Services"
                                        value="Services"
                                      />
                                      <label
                                        class="form-check-label"
                                        for="Services"
                                        >Services</label
                                      >
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="form.procurementCategory"
                                        id="Works"
                                        value="Works"
                                      />
                                      <label
                                        class="form-check-label"
                                        for="Works"
                                        >Works</label
                                      >
                                    </div>
                                    <!-- <div
                                  class="invalid-feedback"
                                  v-if="
                                    !$v.form.procurementCategory
                                      .procurementCategoryMustBeSelected
                                  "
                                >
                                  Choose one of the procurement category
                                </div> -->

                                    <div class="form-group">
                                      <label for="briefDescription"
                                        >Brief Description about
                                        organisations</label
                                      >
                                      <textarea
                                        class="form-control"
                                        :class="{
                                          'is-invalid':
                                            $v.form.briefDescription.$error,
                                        }"
                                        v-model="form.briefDescription"
                                        rows="3"
                                      ></textarea>
                                      <div
                                        class="invalid-feedback"
                                        v-if="
                                          !$v.form.briefDescription.required
                                        "
                                      >
                                        Organisation Description field is
                                        required
                                      </div>
                                    </div>
                                  </fieldset>
                                  <div class="row">
                                    <div class="col-md-12">
                                      <fieldset>
                                        <legend>
                                          Your Organization Registration
                                        </legend>
                                        <hr />
                                        <div class="row">
                                          <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="taxId">Tax ID</label>
                                              <input
                                                type="text"
                                                class="form-control"
                                                :class="{
                                                  'is-invalid':
                                                    $v.form.taxId.$error,
                                                }"
                                                placeholder="Enter Tax Id"
                                                v-model="form.taxId"
                                              />
                                              <div
                                                class="invalid-feedback"
                                                v-if="!$v.form.taxId.required"
                                              >
                                                Tax ID is required
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
                                                :class="{
                                                  'is-invalid':
                                                    $v.form.registrationNumber
                                                      .$error,
                                                }"
                                                placeholder="Enter Registration Number"
                                                v-model="
                                                  form.registrationNumber
                                                "
                                              />
                                              <div
                                                class="invalid-feedback"
                                                v-if="
                                                  !$v.form.registrationNumber
                                                    .required
                                                "
                                              >
                                                Registration number is required
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label for="country">Country</label>
                                          <select
                                            class="form-control"
                                            :class="{
                                              'is-invalid':
                                                $v.form.country.$error,
                                            }"
                                            v-model="form.country"
                                          >
                                            <option value="">
                                              Choose a country
                                            </option>
                                            <option
                                              v-for="country in allCountries"
                                              :key="country.name"
                                              :value="country.name"
                                            >
                                              {{ country.name }}
                                            </option>
                                          </select>
                                          <div
                                            class="invalid-feedback"
                                            v-if="!$v.form.country.required"
                                          >
                                            Country field is required
                                          </div>
                                        </div>
                                      </fieldset>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-7">
                                <div class="custom-border-profile">
                                  <div class="row">
                                    <div class="col-md-12">
                                      <fieldset>
                                        <legend>Your Account Details</legend>
                                        <hr />
                                        <div class="form-group">
                                          <label for="userName">Username</label>
                                          <input
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid':
                                                $v.form.userName.$error,
                                            }"
                                            placeholder="Enter Username(Minimum with 6 characters)"
                                            v-model="form.userName"
                                          />
                                          <div
                                            class="invalid-feedback"
                                            v-if="!$v.form.userName.required"
                                          >
                                            Username is required
                                          </div>
                                          <div
                                            class="invalid-feedback"
                                            v-if="!$v.form.userName.minLength"
                                          >
                                            Username should not be less than 6
                                            characters
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="password"
                                                >Password</label
                                              >
                                              <input
                                                type="password"
                                                class="form-control"
                                                :class="{
                                                  'is-invalid':
                                                    $v.form.password.$error,
                                                }"
                                                placeholder="Enter Password(Minimum with 8 characters)"
                                                v-model="form.password"
                                              />
                                              <div
                                                class="invalid-feedback"
                                                v-if="
                                                  !$v.form.password.required
                                                "
                                              >
                                                Password is required
                                              </div>
                                              <div
                                                class="invalid-feedback"
                                                v-if="
                                                  !$v.form.password.minLength
                                                "
                                              >
                                                Password should not be less than
                                                8 characters
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
                                                :class="{
                                                  'is-invalid':
                                                    $v.form.confirmPassword
                                                      .$error,
                                                }"
                                                placeholder="Enter Password(Minimum with 8 characters)"
                                                v-model="form.confirmPassword"
                                              />
                                              <div
                                                class="invalid-feedback"
                                                v-if="
                                                  !$v.form.confirmPassword
                                                    .required
                                                "
                                              >
                                                Confirm Password field is
                                                required
                                              </div>
                                              <div
                                                class="invalid-feedback"
                                                v-if="
                                                  !$v.form.confirmPassword
                                                    .sameAsPassword
                                                "
                                              >
                                                Passwords Do not Match
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="form-group">
                                          <label for="email">Email</label>
                                          <input
                                            type="email"
                                            class="form-control"
                                            :class="{
                                              'is-invalid':
                                                $v.form.email.$error,
                                            }"
                                            placeholder="Enter Email Address"
                                            v-model="form.email"
                                          />
                                          <div
                                            class="invalid-feedback"
                                            v-if="!$v.form.email.required"
                                          >
                                            Email Address field is required
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
                                                :class="{
                                                  'is-invalid':
                                                    $v.form.companyPhoneNumber
                                                      .$error,
                                                }"
                                                placeholder="Enter Phone Number"
                                                v-model="
                                                  form.companyPhoneNumber
                                                "
                                              />
                                              <div
                                                class="invalid-feedback"
                                                v-if="
                                                  !$v.form.companyPhoneNumber
                                                    .required
                                                "
                                              >
                                                Phone Number field is required
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                              <label
                                                for="alternativeCompanyPhoneNumber"
                                                >Alternative Company Phone
                                                Number(Optional)</label
                                              >
                                              <input
                                                type="number"
                                                class="form-control"
                                                placeholder="Enter Alternative Phone Number"
                                                v-model="
                                                  form.alternativeCompanyPhoneNumber
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
                                                :class="{
                                                  'is-invalid':
                                                    $v.form.secretQuestion
                                                      .$error,
                                                }"
                                                v-model="form.secretQuestion"
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
                                                class="invalid-feedback"
                                                v-if="
                                                  !$v.form.secretQuestion
                                                    .required
                                                "
                                              >
                                                Secret Question field is
                                                required
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
                                                :class="{
                                                  'is-invalid':
                                                    $v.form.secretAnswer.$error,
                                                }"
                                                placeholder="Enter Your Answer"
                                                v-model="form.secretAnswer"
                                              />
                                              <div
                                                class="invalid-feedback"
                                                v-if="
                                                  !$v.form.secretAnswer.required
                                                "
                                              >
                                                Secret Answer field is required
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

                            <div class="row">
                              <div class="col-md-12">
                                <div class="custom-border2-profile">
                                  <fieldset>
                                    <legend>Your Contact Address:</legend>
                                    <hr />
                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="firstName"
                                            >Enter First Name</label
                                          >
                                          <input
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid':
                                                $v.form.firstName.$error,
                                            }"
                                            placeholder="Enter First Name"
                                            v-model="form.firstName"
                                          />
                                          <div
                                            class="invalid-feedback"
                                            v-if="!$v.form.firstName.required"
                                          >
                                            First Name field is required
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="lastName"
                                            >Enter Last Name</label
                                          >
                                          <input
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid':
                                                $v.form.lastName.$error,
                                            }"
                                            placeholder="Enter Last Name"
                                            v-model="form.lastName"
                                          />
                                          <div
                                            class="invalid-feedback"
                                            v-if="!$v.form.lastName.required"
                                          >
                                            Last Name field is required
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="address"
                                            >Enter Address</label
                                          >
                                          <input
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid':
                                                $v.form.address.$error,
                                            }"
                                            placeholder="Enter Your Address"
                                            v-model="form.address"
                                          />
                                          <div
                                            class="invalid-feedback"
                                            v-if="!$v.form.address.required"
                                          >
                                            Address field is required
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="city">City</label>
                                          <input
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid': $v.form.city.$error,
                                            }"
                                            placeholder="Enter Code Sent To Your Email"
                                            v-model="form.city"
                                          />
                                          <div
                                            class="invalid-feedback"
                                            v-if="!$v.form.city.required"
                                          >
                                            City field is required
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
                                            :class="{
                                              'is-invalid':
                                                $v.form.region.$error,
                                            }"
                                            placeholder="Enter Your Current Region"
                                            v-model="form.region"
                                          />
                                          <div
                                            class="invalid-feedback"
                                            v-if="!$v.form.region.required"
                                          >
                                            Region field is required
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="zipCode">Zip Code</label>
                                          <input
                                            type="text"
                                            class="form-control"
                                            :class="{
                                              'is-invalid':
                                                $v.form.zipCode.$error,
                                            }"
                                            placeholder="Enter Zip Code"
                                            v-model="form.zipCode"
                                          />
                                          <div
                                            class="invalid-feedback"
                                            v-if="!$v.form.zipCode.required"
                                          >
                                            Zip Code field is required
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label for="originCountry">Country</label>
                                      <select
                                        class="form-control"
                                        :class="{
                                          'is-invalid':
                                            $v.form.originCountry.$error,
                                        }"
                                        v-model="form.originCountry"
                                      >
                                        <option value="">
                                          Choose a country
                                        </option>
                                        <option
                                          v-for="country in allCountries"
                                          :key="country.name"
                                          :value="country.name"
                                        >
                                          {{ country.name }}
                                        </option>
                                      </select>
                                      <div
                                        class="invalid-feedback"
                                        v-if="!$v.form.originCountry.required"
                                      >
                                        Country field is required
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>
                              </div>
                            </div>
                            <br />
                            <div class="float-right">
                              <button
                                type="submit"
                                class="btn btn-success update-profile"
                              >
                                Update Profile
                              </button>
                            </div>
                          </form>
                        </div>
                        <div
                          class="tab-pane fade"
                          id="profile"
                          role="tabpanel"
                          aria-labelledby="profile-tab"
                        >
                          <div class="float-right">
                            <button
                              class="btn btn-success btn-sm"
                              @click="addNewApprover"
                            >
                              <i class="ti-plus"></i>
                              Add Approver
                            </button>
                          </div>
                          <div class="table-responsive">
                            <form @submit.prevent="AddProcurementPlanDetails">
                              <table
                                class="table table-striped table-condensed"
                              >
                                <thead>
                                  <tr>
                                    <th>Select Approver</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr
                                    v-for="(approver, index) in form2.approvers"
                                    :key="index"
                                  >
                                    <td>
                                      <div class="form-group">
                                        <label for=""
                                          >Approver {{ index + 1 }}</label
                                        >
                                        <select
                                          class="form-control"
                                          v-model="approver.approver_user_id"
                                          required
                                        >
                                          <option value="">
                                            Choose Approver
                                          </option>
                                          <option
                                            v-for="(
                                              company_user, index
                                            ) in procurement_plan_approvers"
                                            :key="index"
                                            :value="company_user.id"
                                          >
                                            {{ company_user.firstName }}
                                            {{ company_user.lastName }}
                                          </option>
                                        </select>
                                      </div>
                                    </td>
                                    <td v-if="index === 0">
                                      <span
                                        class="
                                          badge badge-primary
                                          default-approver
                                        "
                                        >Default Approver</span
                                      >
                                    </td>
                                    <td v-if="index != 0">
                                      <button
                                        class="
                                          btn btn-danger
                                          remove-approver
                                          btn-sm
                                        "
                                        @click="deleteRow(index)"
                                      >
                                        <i class="ti-trash"></i>
                                        Remove Approver
                                      </button>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <hr />
                              <br />
                              <div class="float-right">
                                <button
                                  class="btn btn-primary btn-sm"
                                  type="submit"
                                >
                                  <i class="ti-save"></i> Submit Approvers
                                </button>
                              </div>
                            </form>
                          </div>
                        </div>
                        <div
                          class="tab-pane fade"
                          id="contact"
                          role="tabpanel"
                          aria-labelledby="contact-tab"
                        >
                          ...
                        </div>
                      </div>
                      <br />
                    </div>
                  </div>
                  <br />
                  <br />
                </div>
              </div>
            </div>
          </div>
        </div>
        <h1 hidden>{{ checkIfUserIsIdle }}</h1>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <FooterComponent :year="year" />
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
  </div>
</template>
  
<script>
import Loading from "vue-loading-overlay";
import UserLoggedOnNavBarComponent from "../NavBar/UserLoggedOnNavBarComponent.vue";
import SideBarComponent from "../SideBar/SideBarComponent.vue";
import FooterComponent from "../Footer/FooterComponent.vue";
import { mapActions, mapState, mapGetters } from "vuex";
import { validationMixin } from "vuelidate";
import {
  required,
  email,
  numeric,
  minLength,
  sameAs,
} from "vuelidate/lib/validators";
import Form from "vform";
import Helpers from "../../Helpers/Helpers";

export default {
  props: {
    secret_questions: Array,
    selected_user: Object,
    procurement_plan_approvers: Array,
  },
  mixins: [validationMixin],
  data() {
    return {
      fullPage: true,
      challengeNumber1: "",
      challengeNumber2: "",
      form2: new Form({
        approvers: [{ approver_user_id: "" }],
      }),
      form: new Form({
        id: "",
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
      }),
    };
  },
  validations: {
    form: {
      organisationName: { required },
      // procurementCategory: {
      //   procurementCategoryMustBeSelected,
      // },
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
      firstName: { required },
      lastName: { required },
      address: { required },
      city: { required },
      region: { required },
      zipCode: { required },
      originCountry: { required },
    },
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
    year() {
      return this.$page.props.year;
    },
    checkIfUserIsIdle() {
      return this.isAppIdle ? true : false;
    },
  },

  methods: {
    ...mapActions("registration", ["showLoader", "hideLoader"]),

    AddProcurementPlanDetails() {
      this.showLoader();
      this.form2
        .post("/procurement_plan/details/approvers")
        .then((response) => {
          Swal.fire({
            icon: "success",
            title: "Response",
            text: response.data.message,
          });
          this.hideLoader();
          window.location.reload();
        })
        .catch((error) => {
          this.hideLoader();
        });
    },

    addNewApprover() {
      this.form2.approvers.push({ approver_user_id: "" });
    },

    deleteRow(index) {
      if (index != 0) {
        this.form2.approvers.splice(index, 1);
      }
    },

    submitRegistration() {
      // stop here if form is invalid
      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      }

      this.showLoader();

      this.form
        .post("/update/provider/or/company", {
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
          Helpers.logoutUser();
        })
        .catch((error) => {
          this.hideLoader();
        });
    },
  },

  created() {
    setTimeout(() => this.hideLoader(), 2000);
    this.form.fill(this.selected_user);
    this.form.zipCode = this.selected_user.zip_code;
  },

  components: {
    UserLoggedOnNavBarComponent,
    SideBarComponent,
    Loading,
    FooterComponent,
  },
};
</script>
  