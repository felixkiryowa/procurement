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
              <h5>Chart Of Accounts Ledger Categories</h5>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item">
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
                </ol>
              </nav>
            </div>

            <div class="card-body">
              <div>
                <button
                  class="btn btn-success btn-sm float-right"
                  @click="openAddLedgerCategoryModal"
                >
                  Add Bank Account
                </button>
              </div>
              <br />
              <br />
              <hr />
              <div class="table-responsive">
                <table
                  id="allBankAccounts"
                  class="table table-bordered table-sm table-striped"
                >
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Account Name</th>
                      <th>Created On</th>
                      <th>Item Code</th>
                      <th>Category</th>
                      <th>Ledger</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      :key="index"
                      v-for="(bank_account, index) in bank_accounts"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>{{ bank_account.account_name }}</td>
                      <td>{{ bank_account.created_at }}</td>
                      <td>{{ bank_account.account_number }}</td>
                      <td v-if="bank_account.category_name == null">
                        <b class="text-danger">NOT APPLICABLE</b>
                      </td>
                      <td v-if="bank_account.category_name !== null">
                        {{ bank_account.category_name }}
                      </td>
                      <td>{{ bank_account.ledger_name }}</td>
                      <td>
                        <button
                          class="btn btn-sm btn-success"
                          @click="updateBankAccount(bank_account)"
                        >
                          Edit
                        </button>
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

    <!-- /.create a ledger-category modal -->
    <div class="modal fade" id="createABankAccount">
      <div class="modal-dialog">
        <form
          @submit.prevent="
            editMode ? updateBankAccountBackend() : saveBankAccount()
          "
          role="form"
        >
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">
                {{
                  editMode ? bank_account_to_edit : "Create A New Bank Account"
                }}
              </h4>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="card card-primary">
                <div class="card-header"></div>
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="">Ledger</label>
                        <select
                          class="form-control input-sm"
                          v-model="form.account_group_id"
                          name="account_group_id"
                          :class="{
                            'is-invalid': form.errors.has('account_group_id'),
                          }"
                        >
                          <option disabled value="">
                            Choose Account Group
                          </option>
                          <option
                            v-for="ledger in ledgers"
                            :key="ledger.id"
                            :value="ledger.id"
                          >
                            {{ ledger.name }}
                          </option>
                        </select>
                        <div
                          v-if="form.errors.has('account_group_id')"
                          class="laravel-error"
                          v-html="form.errors.get('account_group_id')"
                        />
                      </div>
                      <div class="form-group">
                        <label for="">Ledger Category</label>
                        <select
                          class="form-control input-sm"
                          v-model="form.ledger_category_id"
                          name="ledger_category_id"
                          :class="{
                            'is-invalid': form.errors.has('ledger_category_id'),
                          }"
                        >
                          <option disabled value="">
                            Choose Ledger Category
                          </option>
                          <option
                            v-for="ledger_category in ledger_categories"
                            :key="ledger_category.ledger_category_id"
                            :value="ledger_category.ledger_category_id"
                          >
                            {{ ledger_category.category_name }}
                          </option>
                        </select>
                        <div
                          v-if="form.errors.has('ledger_category_id')"
                          class="laravel-error"
                          v-html="form.errors.get('ledger_category_id')"
                        />
                      </div>
                      <div class="form-group">
                        <label for="role_name">Account Name</label>
                        <input
                          type="text"
                          class="form-control"
                          name="account_name"
                          :class="{
                            'is-invalid': form.errors.has('account_name'),
                          }"
                          v-model="form.account_name"
                          placeholder="Enter Account Name"
                        />
                        <div
                          v-if="form.errors.has('account_name')"
                          class="laravel-error"
                          v-html="form.errors.get('account_name')"
                        />
                      </div>
                      <div class="form-group">
                        <label for="role_name">Item Code</label>
                        <input
                          type="text"
                          class="form-control"
                          name="item_code"
                          :class="{
                            'is-invalid': form.errors.has('item_code'),
                          }"
                          v-model="form.item_code"
                          placeholder="Enter Item Code"
                        />
                        <div
                          v-if="form.errors.has('item_code')"
                          class="laravel-error"
                          v-html="form.errors.get('item_code')"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                Close
              </button>
              <button type="submit" class="btn btn-success float-right">
                {{ editMode ? "Update" : "Save" }}
              </button>
            </div>
          </div>
        </form>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
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
    ledger_categories: Array,
    bank_accounts: Array,
  },
  data() {
    return {
      fullPage: true,
      editMode: false,
      bank_account_to_edit: "",
      form: new Form({
        bank_id: "",
        ledger_category_id: "",
        account_group_id: "",
        account_name: "",
        item_code:""
      }),
    };
  },
  mounted() {
    $("#allBankAccounts").DataTable();
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

    saveBankAccount() {
      this.$Progress.fail();
      this.showLoader();
      this.form
        .post("/create/bank/account")
        .then((response) => {
          Swal.fire({
            icon: "success",
            title: "Add New Bank Account",
            text: response.data.message,
          });

          this.hideLoader();
          $("#createABankAccount").modal("hide");
          this.form.reset();
          window.location.href = "/manage/bank/accounts";
          $("#allBankAccounts").DataTable();
        })
        .catch((error) => {
          this.$Progress.fail();
          this.hideLoader();
        });
    },

    openAddLedgerCategoryModal() {
      this.editMode = false;
      $("#createABankAccount").modal("show");
    },

    updateBankAccount(bank_account) {
      this.form.fill(bank_account);
      this.editMode = true;

      console.log(bank_account);

      this.bank_account_to_edit =
        "Update " + bank_account.bank_account_name + " Bank Account";
      // this.form.account_name = bank_account.bank_account_name;
      $("#createABankAccount").modal("show");
    },

    updateBankAccountBackend() {
      this.$Progress.fail();
      this.showLoader();
      this.form
        .post("/update/bank/account")
        .then((response) => {
          Swal.fire({
            icon: "success",
            title: "Update Bank Account",
            text: response.data.message,
          });

          this.hideLoader();
          $("#createABankAccount").modal("hide");
          this.form.reset();
          window.location.href = "/manage/bank/accounts";
          $("#allBankAccounts").DataTable();
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
  },
};
</script>
