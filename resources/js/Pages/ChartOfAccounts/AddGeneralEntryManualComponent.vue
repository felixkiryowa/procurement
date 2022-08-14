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
              <h5>Add General Ledger Entry</h5>
            </div>

            <div class="card-body">
              <hr />
              <form @submit.prevent="saveManualAddingLedger" role="form">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="exampleFormControlTextarea1">Comment</label>
                      <textarea
                        class="form-control"
                        id="comment"
                        name="comment"
                        v-model="form.comment"
                        :class="{
                          'is-invalid': form.errors.has('comment'),
                        }"
                        rows="3"
                      ></textarea>
                    </div>
                    <div
                      v-if="form.errors.has('comment')"
                      class="laravel-error"
                      v-html="form.errors.get('comment')"
                    />
                    <hr />
                    <h5><b>AFFECTED GL ENTRIES</b></h5>
                    <br />
                    <div class="row">
                      <div class="col-md-6">
                        <b class="text-success"></b>
                      </div>
                    </div>
                    <hr />
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="role_name">ACCOUNT TO DEBIT</label>
                          <br />
                          <v-select
                            label="name"
                            :class="{
                              'is-invalid': form.errors.has('debit_account'),
                            }"
                            :options="all_accounts"
                            @input="setSelectedDebitAccount"
                          ></v-select>
                          <div v-if="debit_account_selected">
                            <b class="text-success"
                              >Available Balance
                              {{ debit_account_balance }} Shs</b
                            >
                          </div>
                          <div
                            v-if="form.errors.has('debit_account')"
                            class="laravel-error"
                            v-html="form.errors.get('debit_account')"
                          />
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="role_name">AMOUNT</label>
                          <input
                            type="number"
                            class="form-control"
                            name="debit_amount"
                            v-model="form.debit_amount"
                            min="0"
                            :class="{
                              'is-invalid': form.errors.has('debit_amount'),
                            }"
                            placeholder="Amount To Debit"
                          />
                          <span class="text-primary">
                            Debit Amount
                            {{ checkDebitAmountValue | formatNumber }}
                          </span>
                          <div
                            v-if="form.errors.has('debit_amount')"
                            class="laravel-error"
                            v-html="form.errors.get('debit_amount')"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="role_name">ACCOUNT TO CREDIT</label>
                          <br />
                          <v-select
                            label="name"
                            :options="all_accounts"
                            :class="{
                              'is-invalid': form.errors.has('credit_account'),
                            }"
                            @input="setSelectedCreditAccount"
                          ></v-select>
                        </div>
                        <div v-if="credit_account_selected">
                          <b class="text-success"
                            >Available Balance
                            {{ credit_account_balance }} Shs</b
                          >
                        </div>
                        <div
                          v-if="form.errors.has('credit_account')"
                          class="laravel-error"
                          v-html="form.errors.get('credit_account')"
                        />
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="role_name">AMOUNT</label>
                          <input
                            type="number"
                            class="form-control"
                            name="credit_amount"
                            v-model="form.credit_amount"
                            min="0"
                            :class="{
                              'is-invalid': form.errors.has('credit_amount'),
                            }"
                            placeholder="Amount To Credit"
                          />
                          <span class="text-primary">
                            Credit Amount
                            {{ checkCreditAmountValue | formatNumber }}
                          </span>
                          <div
                            v-if="form.errors.has('credit_amount')"
                            class="laravel-error"
                            v-html="form.errors.get('credit_amount')"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12" v-if="credit_account_selected">
                        <b class="text-success"></b>
                      </div>
                    </div>

                    <br />
                    <button type="submit" class="btn btn-success float-right">
                      Save
                    </button>
                  </div>
                </div>
              </form>
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
    all_accounts: Array,
  },
  data() {
    return {
      fullPage: true,
      debit_account_selected: false,
      credit_account_selected: false,
      debit_account_balance: 0,
      credit_account_balance: 0,
      form: new Form({
        debit_amount: "",
        credit_amount: "",
        credit_account: "",
        debit_account: "",
        comment: "",
      }),
    };
  },
  mounted() {
    $("#allSubSubBankAccounts").DataTable();
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

    checkDebitAmountValue() {
      return this.form.debit_amount === "" ? 0 : this.form.debit_amount;
    },
    checkCreditAmountValue() {
      return this.form.credit_amount === "" ? 0 : this.form.credit_amount;
    },
    ...mapState("chartofaccounts", ["isLoading"]),
  },

  methods: {
    ...mapActions("chartofaccounts", ["showLoader", "hideLoader"]),

    setSelectedCreditAccount(value) {
      this.form.credit_account = value.account_number;
      var nf = new Intl.NumberFormat();
      axios
        .get("/get/balance/on/account/" + value.account_number)
        .then((response) => {
          this.credit_account_selected = true;

          this.credit_account_balance =
            " on " + value.name + " account is " + nf.format(response.data);
        })
        .catch((error) => {});
    },
    setSelectedDebitAccount(value) {
      this.form.debit_account = value.account_number;
      var nf = new Intl.NumberFormat();
      axios
        .get("/get/balance/on/account/" + value.account_number)
        .then((response) => {
          this.debit_account_selected = true;

          this.debit_account_balance =
            " on " + value.name + " account is  " + nf.format(response.data);
        })
        .catch((error) => {});
    },

    saveManualAddingLedger() {
      this.$Progress.fail();
      this.showLoader();
      this.form
        .post("/create/general/entry")
        .then((response) => {
          Swal.fire({
            icon: "success",
            title: "Add New General Entry",
            text: response.data.message,
          });
          this.form.credit_account = "";
          this.form.debit_account = "";
          this.debit_account_selected = false;
          this.credit_account_selected = false;
          this.hideLoader();
          this.form.reset();
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
