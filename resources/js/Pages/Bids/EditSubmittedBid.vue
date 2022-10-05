<template>
  <div class="container-scroller">
    <UserLoggedOnNavBarComponent :app="app" :user="user" />
    <div class="container-fluid page-body-wrapper">
      <!-- partial -->
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
                <div class="col-lg-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">
                        Bid Amount :
                        {{ bid.budget_amount | formatNumber }} | Procurement
                        Plan
                        {{ getFormattedDate(bid.financial_year_start) }}
                        -
                        {{ getFormattedDate(bid.financial_year_end) }}

                        | Bid Reference {{ bid.reference_number }}
                      </h4>
                    </div>
                    <div class="card-body">
                      <div class="alert alert-primary" role="alert">
                        Description : {{ bid.name }}
                      </div>
                      <form @submit.prevent="submitProvidersBid">
                        <div class="form-group">
                          <label for="">Summary</label>
                          <textarea
                            v-model="form.brief_description"
                            class="form-control"
                            cols="30"
                            rows="5"
                            required
                          ></textarea>
                        </div>
                        <table class="table table-striped table-condensed">
                          <thead>
                            <tr>
                              <th>Add Attachment</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr
                              v-for="(uploaded_file, index) in documents"
                              :key="index"
                            >
                              <td>Uploaded Document {{ index + 1 }}</td>
                              <td>
                                <a
                                  :href="
                                    getUploadedFile(uploaded_file.document)
                                  "
                                  download=""
                                  >{{ uploaded_file.document }}</a
                                >
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <br />
                        <div class="float-right">
                          <button
                            class="btn btn-success btn-sm"
                            @click="addNewAttachment($event)"
                          >
                            <i class="ti-plus"></i>
                            Add Attachment
                          </button>
                        </div>
                        <table class="table table-striped table-condensed">
                          <thead>
                            <tr>
                              <th>Add Attachment</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr
                              v-for="(
                                uploaded_file, index
                              ) in form.uploaded_files"
                              :key="index"
                            >
                              <td>
                                <div class="form-group">
                                  <label for="">Document {{ index + 1 }}</label>
                                  <input
                                    type="file"
                                    required
                                    @change="handleFileUpload($event, index)"
                                    class="form-control form-control-file"
                                  />
                                </div>
                              </td>
                              <td v-if="index === 0">
                                <span
                                  class="badge badge-primary default-approver"
                                  >Default Attachment</span
                                >
                              </td>
                              <td v-if="index != 0">
                                <button
                                  class="btn btn-danger remove-approver btn-sm"
                                  @click="deleteRow(index)"
                                >
                                  <i class="ti-trash"></i>
                                  Remove Attachment {{ index }}
                                </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <h3>Validity Period Of Your Bid</h3>
                        <br />
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="">Start Date</label>
                              <input
                                type="date"
                                required
                                v-model="form.start_date"
                                class="form-control"
                              />
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="">End Date</label>
                              <input
                                type="date"
                                required
                                v-model="form.end_date"
                                class="form-control"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="">Bid Currency</label>
                              <select
                                v-model="form.currency"
                                class="form-control"
                                required
                              >
                                <option value="">Choose Currency</option>
                                <option
                                  v-for="(currency, index) in allCurrencies"
                                  :key="index"
                                  :value="currency.cc"
                                >
                                  {{ currency.cc }} / {{ currency.name }}
                                </option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="">Amount</label>
                              <input
                                type="number"
                                placeholder="Enter Amount"
                                v-model="form.amount"
                                required
                                class="form-control"
                              />
                            </div>
                            <b class="amount-input">
                              Amount : {{ checkInputValue | formatNumber }}
                              {{ form.currency }}
                            </b>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="">Status</label>
                          <select
                            class="form-control"
                            v-model="form.status"
                            required
                          >
                            <option selected value="">Choose Status</option>
                            <option value="saved">Saved</option>
                            <option value="published">Submitted</option>
                          </select>
                        </div>
                        <div class="float-right">
                          <button type="submit" class="btn btn-success">
                            Save
                          </button>
                        </div>
                        <br />
                        <br />
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <h1 hidden>{{ checkIfUserIsIdle }}</h1>
        </div>
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
import UserLoggedOnNavBarComponent from "../NavBar/UserLoggedOnNavBarComponent.vue";
import SideBarComponent from "../SideBar/SideBarComponent.vue";
import FooterComponent from "../Footer/FooterComponent.vue";
import Loading from "vue-loading-overlay";
import { mapActions, mapState, mapGetters } from "vuex";
import moment from "moment";
import Swal from "sweetalert2";

export default {
  props: {
    bid: Object,
    submittted_bid: Object,
    documents: Array,
  },
  //   mixins: [ValidationHelper],
  data() {
    return {
      fullPage: true,
      file_count: 0,
      form: new Form({
        id: "",
        brief_description: "",
        start_date: "",
        end_date: "",
        amount: "",
        currency: "",
        status: "",
        tender_notice_id: "",
        uploaded_files: [
          {
            id: 0,
            file: "",
          },
        ],
      }),
    };
  },
  mounted() {},
  computed: {
    ...mapState("registration", ["isLoading"]),
    ...mapGetters("registration", ["allCurrencies"]),

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

    checkInputValue() {
      return this.form.amount === "" ? 0 : this.form.amount;
    },
  },

  methods: {
    ...mapActions("registration", ["showLoader", "hideLoader"]),

    getFormattedDate(date) {
      return moment(date).format("YYYY");
    },

    addNewAttachment(event) {
      event.preventDefault();
      this.file_count = this.file_count + 1;
      this.form.uploaded_files.push({ id: this.file_count, file: "" });
    },

    deleteRow(index) {
      if (index != 0) {
        this.form.uploaded_files.splice(index, 1);
      }
    },

    handleFileUpload(event, passed_index) {
      let selected_file = event.target.files[0];
      let fileName = selected_file.name;
      let extension = fileName.substring(fileName.lastIndexOf(".") + 1);

      if (extension === "pdf" || extension === "doc") {
        let objIndex = this.form.uploaded_files.findIndex(
          (obj) => obj.id == passed_index
        );

        let reader = new FileReader();
        reader.onloadend = () => {
          this.form.uploaded_files[objIndex].file = reader.result;
        };

        reader.readAsDataURL(selected_file);
      } else {
        Swal.fire({
          icon: "error",
          title: "Inavlid Uploaded File Format",
          text: "Uploaded Attachment Should Be A PDF Or Word File",
        });
      }
    },
    getUploadedFile(document) {
      let uploadeFile = "/bid_documents/" + document;
      return uploadeFile;
    },

    submitProvidersBid() {
      this.showLoader();
      this.form
        .post("/update/provider/bid", {
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
          window.location.href = "/manage/submitted/bids";
        })
        .catch((error) => {
          this.hideLoader();
        });
    },
  },

  created() {
    setTimeout(() => {
      this.hideLoader();
    }, 3000);

    this.form.fill(this.submittted_bid);
    this.form.uploaded_files = [
      {
        id: 0,
        file: "",
      },
    ];
    // this.form.tender_notice_id = this.bid.id;
  },

  components: {
    UserLoggedOnNavBarComponent,
    SideBarComponent,
    Loading,
    FooterComponent,
  },
};
</script>