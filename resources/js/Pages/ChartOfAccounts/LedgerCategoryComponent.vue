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
                  <li class="breadcrumb-item active">
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
              <div>
                <button
                  class="btn btn-success btn-sm float-right"
                  @click="openAddLedgerCategoryModal"
                >
                  Add Category
                </button>
              </div>
              <br />
              <br />
              <hr />
              <div class="table-responsive">
                <table
                  id="allLedgerCategoriesSubCategories"
                  class="table table-bordered table-sm table-striped"
                >
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Ledger Name</th>
                      <th>Category Name</th>
                      <th>Created On</th>
                      <th>Item Code</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      :key="index"
                      v-for="(ledger_category, index) in ledger_categories"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>{{ ledger_category.name }}</td>
                      <td>{{ ledger_category.category_name }}</td>
                      <td>{{ ledger_category.created_at }}</td>
                      <td>{{ ledger_category.item_code }}</td>
                      <td>
                        <button
                          class="btn btn-sm btn-success"
                          @click="updateLedgerCategory(ledger_category)"
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
    <div class="modal fade" id="createALedgerCategory">
      <div class="modal-dialog">
        <form
          @submit.prevent="
            editMode ? updateLedgerCategoryBackend() : saveLedgerCategory()
          "
          role="form"
        >
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">
                {{
                  editMode
                    ? ledger_category_to_edit
                    : "Create a new ledger category"
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
                        <label for="role_name">Category Name</label>
                        <input
                          type="text"
                          class="form-control"
                          name="category_name"
                          :class="{
                            'is-invalid': form.errors.has('category_name'),
                          }"
                          v-model="form.category_name"
                          placeholder="Enter Category Name"
                        />
                        <div
                          v-if="form.errors.has('category_name')"
                          class="laravel-error"
                          v-html="form.errors.get('category_name')"
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
  },
  data() {
    return {
      fullPage: true,
      editMode: false,
      ledger_category_to_edit: "",
      form: new Form({
        ledger_categories_id: "",
        account_group_id: "",
        category_name: "",
        item_code:"",
      }),
    };
  },
  mounted() {
    $("#allLedgerCategoriesSubCategories").DataTable();
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

    saveLedgerCategory() {
      this.$Progress.fail();
      this.showLoader();
      this.form
        .post("/create/ledger/category")
        .then((response) => {
          Swal.fire({
            icon: "success",
            title: "Add New Ledger Category",
            text: response.data.message,
          });

          this.hideLoader();
          $("#createALedgerCategory").modal("hide");
          this.form.reset();
          window.location.href = "/manage/ledger/categories";
          $("#allLedgerCategoriesSubCategories").DataTable();
        })
        .catch((error) => {
          this.$Progress.fail();
          this.hideLoader();
        });
    },

    openAddLedgerCategoryModal() {
      this.editMode = false;
      $("#createALedgerCategory").modal("show");
    },

    updateLedgerCategory(ledger_category) {
      this.form.fill(ledger_category);
      this.editMode = true;

      this.ledger_category_to_edit =
        "Update " + ledger_category.category_name + " Ledger Category";
      $("#createALedgerCategory").modal("show");
    },

    updateLedgerCategoryBackend() {
      this.$Progress.fail();
      this.showLoader();
      this.form
        .post("/update/ledger/category")
        .then((response) => {
          Swal.fire({
            icon: "success",
            title: "Update Ledger Category",
            text: response.data.message,
          });

          this.hideLoader();
          $("#createALedgerCategory").modal("hide");
          this.form.reset();
          window.location.href = "/manage/ledger/categories";
          $("#allLedgerCategoriesSubCategories").DataTable();
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
