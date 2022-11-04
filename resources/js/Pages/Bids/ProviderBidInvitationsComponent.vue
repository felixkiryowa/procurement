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
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">
                    Welcome {{ user.firstName }} {{ user.lastName }}
                  </h3>
                </div>

                <div class="col-lg-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">
                        Published Invitation for Bids/Quotations List
                      </h4>
                      <div class="table-responsive pt-3">
                        <table
                          id="allPlans"
                          class="table table-bordered table-sm table-striped"
                        >
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Subject</th>
                              <th>Procurement Plan</th>
                              <th>Procurement Type</th>
                              <th>Deadline</th>
                              <!-- <th>Display Start</th>
                              <th>Display End</th> -->
                              <th>Status</th>
                              <th>Date Created</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr :key="index" v-for="(detail, index) in bids">
                              <td>{{ index + 1 }}</td>
                              <td>{{ detail.name }}</td>
                              <td>
                                Procurement Plan
                                {{
                                  getFormattedDate(detail.financial_year_start)
                                }}
                                -
                                {{
                                  getFormattedDate(detail.financial_year_end)
                                }}
                              </td>
                              <td>{{ detail.type }}</td>
                              <td>
                                {{ new Date(detail.deadline).toLocaleString() }}
                              </td>
                              <!-- <td>
                                {{
                                  new Date(
                                    detail.display_start_date
                                  ).toLocaleString()
                                }}
                              </td>
                              <td>
                                {{
                                  new Date(
                                    detail.display_end_date
                                  ).toLocaleString()
                                }}
                              </td> -->
                              <td>
                                <span class="badge badge-success">{{
                                  detail.status
                                }}</span>
                              </td>
                              <td>{{ detail.created_at | customDate }}</td>
                              <td>
                                <button
                                  @click="previewBidDetails(detail)"
                                  class="btn btn-sm btn-success"
                                >
                                  View Details
                                </button>
                                |
                                <button
                                  @click="
                                    submitBid(
                                      detail.id,
                                      detail.reference_number
                                    )
                                  "
                                  class="btn btn-sm btn-success"
                                >
                                  Submit Bid
                                </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
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
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
import { mapActions, mapState, mapGetters } from "vuex";
import moment from "moment";

export default {
  props: {
    bids: Array,
  },
  //   mixins: [ValidationHelper],
  data() {
    return {
      fullPage: true,
      bid_details: {},
    };
  },
  mounted() {
    $("#allPlans").DataTable();
  },
  computed: {
    ...mapState("registration", ["isLoading"]),
    //   ...mapGetters("registration", ["allCountries"]),

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

    getFormattedDate(date) {
      return moment(date).format("YYYY");
    },

    previewBidDetails(detail) {
      // this.bid_details = detail;
      window.location.href = '/company/tender/notice/details/'+btoa(detail.id)

      // $("#tenderNoticeDetailsModal").modal("show");
    },

    submitBid(id, reference_number) {
      this.showLoader();
      axios
        .get("/check/user/submitted/bid/already/" + id, {
          headers: {
            "X-Frame-Options": "sameorigin",
            "X-Content-Type-Options": "nosniff",
            "strict-transport-security": "max-age=31536000",
          },
        })
        .then((response) => {
          if (response.data.success) {
            window.location.href = "/submit/bid/" + btoa(id);
            this.hideLoader();
          } else {
            Swal.fire({
              icon: "error",
              title: "Submit Bid Response",
              text:
                "Already Submitted Bid For Tender Notice With Reference " +
                reference_number,
            });
            this.hideLoader();
          }
        })
        .catch((error) => {});
    },
  },

  created() {
    setTimeout(() => {
      this.hideLoader();
    }, 3000);
  },

  components: {
    UserLoggedOnNavBarComponent,
    SideBarComponent,
    Loading,
    FooterComponent,
  },
};
</script>