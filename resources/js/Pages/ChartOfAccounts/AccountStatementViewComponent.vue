<template>
  <div>
    <NavBarComponent :appName="app" :user="user" />
    <br />
    <br />
    <br />
    <div class="container-fluid">
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
          <div class="card">
            <div class="card-header">
              <h5>Cash Book</h5>
            </div>

            <div class="card-body custom-card-body">
              <hr />
              <form @submit.prevent="getLedgers" role="form">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                                            <label for="exampleInputPassword1">Account Number</label>
                    <select
                                                class="form-control custom-form-inputs"
                                                name="account_number"
                                                id="debit_account"
                                                data-width="auto"
                                                data-live-search="true"
                                                >
                                                <option value="">--Select Account Name --</option>

                                                <option
                                                v-for="ledger in all_accounts"
                                                :key="ledger.account_number"
                                                :value="ledger.account_number"
                                            >
                                                {{ ledger.name }}
                                            </option>
                                                </select>
                                                </div>
                  </div>
                   <div class="col-md-3">
                                           <div class="form-group">
                                               <label for="exampleInputEmail1">From Date</label>
                                               <datepicker  name="statement_start_date"

                                               placeholder="Enter From Date"></datepicker>

                                           </div>
                    </div>

                    <div class="col-md-3">
                                           <div class="form-group">
                                               <label for="exampleInputEmail1">To Date</label>
                                               <datepicker  name="statement_end_date"
                                                placeholder="Enter To Date"
                                               ></datepicker>


                                           </div>
                                       </div>

                  <div class="col-md-3">
                    <div class="form-group">

                    </div>
                    <br />
                    <button
                      type="submit"

                      class="btn btn-success "
                    >
                      Generate
                    </button>
                  </div>


                </div>
              </form>
            </div>
          </div>
          <div>Accounts</div>
          <br />
          <br />
        </div>
      </div>
    </div>

    <h1 hidden>{{ checkIfUserIsIdle }}</h1>
  </div>
</template>

<script>
import NavBarComponent from "../NavBar/NavBarComponent.vue";
import Loading from "vue-loading-overlay";
import { mapActions, mapState, mapGetters } from "vuex";
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
import Datepicker from "vuejs-datepicker";

export default {
  props: {
    all_accounts: Array,
  },

  data() {
    return {
      fullPage: true,
      isUploadedFileExcel: false,
      file_attachment: "",
      form: new Form({
        file_attachment: "",
      }),
    };
  },

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

    ...mapState("chartofaccounts", ["isLoading"]),

  },

  methods: {
    ...mapActions("chartofaccounts", ["showLoader", "hideLoader"]),
    getLedgers() {
      this.$Progress.fail();
      this.showLoader();
      const formData = new FormData();
      const headers = { "Content-Type": "multipart/form-data" };
      axios
        .post("/account/generate/statement", formData, { headers })
        .then((response) => {
            Swal.fire({
              icon: "success",
              title: "Generating Report",
              text: response.data.message,
            });
            this.hideLoader();
        })
        .catch((error) => {
          this.$Progress.fail();
          this.hideLoader();
        });
    },
  },
  created() {
    setTimeout(() => {
      this.hideLoader();
    }, 2000);
  },

  components: {
    NavBarComponent,
    Loading,
    Datepicker,
  },
};
</script>
