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
              <h5>Manage Chart Of Accounts</h5>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">
                    <a href="/manage/chartofaccounts">Account Groups</a>
                  </li>
                  <li class="breadcrumb-item">
                    <a href="/manage/ledger/categories">Ledger Categories</a>
                  </li>
                  <li class="breadcrumb-item active">
                    <a href="/manage/bank/accounts">Bank Accounts</a>
                  </li>
                  <li class="breadcrumb-item active">
                    <a href="/manage/sub/bank/accounts">Sub Bank Accounts</a>
                  </li>
                  <li class="breadcrumb-item active">
                    <a href="/manage/sub/sub/bank/accounts"
                      >Sub Sub Bank Accounts</a
                    >
                  </li>
                  <li class="breadcrumb-item active">
                    <a href="/manage/sub/sub/sub/bank/accounts"
                      >Sub Sub Sub Bank Accounts</a
                    >
                  </li>
                </ol>
              </nav>
            </div>

            <div class="card-body">
              <hr />
              <div class="table-responsive">
                <table
                  id="allLedgers"
                  class="table table-bordered table-striped table-sm"
                >
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Created On</th>
                      <!-- <th>Item Code</th> -->
                      <th>Details</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr :key="index" v-for="(ledger, index) in ledgers">
                      <td>{{ index + 1 }}</td>
                      <td>{{ ledger.name }}</td>
                      <td>{{ ledger.created_at }}</td>
                      <!-- <td>{{ ledger.item_code }}</td> -->

                      <td>
                        <a
                          class="btn btn-sm btn-success"
                          href="accounting/accounting-groups/details/"
                        >
                          Account Group Details
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
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
export default {
  props: {
    ledgers: Array,
  },
  data() {
    return {
      fullPage: true,
    };
  },
  mounted() {
    $("#allLedgers").DataTable();
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
  },
  created() {
    setTimeout(() => {
      this.hideLoader();
    }, 2000);

    Fire.$on("afterCreatingLedgerCategory", () => {
      this.fetchSubCategories();
    });
  },

  components: {
    NavBarComponent,
    Loading,
  },
};
</script>
